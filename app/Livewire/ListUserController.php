<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListUserController extends Component
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
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== null, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->paginate(10);

        return view('livewire.list-user', compact('users'));
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            session()->flash('message', 'User deleted successfully!');
        } else {
            session()->flash('error', 'User not found!');
        }
    }
}
