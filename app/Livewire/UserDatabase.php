<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\HistoryLog;
use App\Models\ActivityLog;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserDatabase extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = null;

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = User::with('role')
            ->when($this->search, function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== null, function ($query) {
                $query->where('status', $this->statusFilter);
            });

        // Role-based filtering
        if (Auth::user()->role_id == 3) {
            $query->whereIn('role_id', [1, 2]);
        }
        elseif (Auth::user()->role_id == 4) {
            $query->whereIn('role_id', [1, 2, 3]);
        }
        elseif (Auth::user()->role_id == 5) {
            $query->where('role_id', '!=', 5);
        }

        $users = $query->paginate(10);
            
        return view('livewire.user-database', [
            'users' => $users]);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            HistoryLog::create([
                'role_id' => Auth::user()->role->id,
                'faculty_id' => $user->faculty->id,
                'program_id' => $user->program->id,
                'activity' => $user->username.' Deleted by '. Auth::user()->username,
            ]);
            ActivityLog::where('user_id', $id)->delete();
            $user->delete();
            session()->flash('message', 'User deleted successfully!');
        } else {
            session()->flash('error', 'User not found!');
        }
    }
}
