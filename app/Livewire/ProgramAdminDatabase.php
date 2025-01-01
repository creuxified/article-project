<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProgramAdminDatabase extends Component
{
    public $isModalOpen = false;
    public $users;
    
    public function mount() 
    {
        if(Auth::user()->role->id == 4){
            $this->users = User::where('faculty_id', Auth::user()->faculty->id)
            ->where('role_id', 3)
            ->with(['faculty', 'program', 'role'])
            ->get();
        }
        elseif(Auth::user()->role->id == 5){
            $this->users = User::where('role_id', 3)
            ->with(['faculty', 'program', 'role'])
            ->get();
        }     
        
    }
    public function render()
    {
        return view('livewire.program-admin-database');
    }
}
