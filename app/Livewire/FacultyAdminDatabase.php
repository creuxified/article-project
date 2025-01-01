<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FacultyAdminDatabase extends Component
{
    public $isModalOpen = false;
    public $users;
    
    public function mount() 
    {
        $this->users = User::where('role_id', 4)
            ->with(['faculty', 'program', 'role'])
            ->get();
        
    }
    public function render()
    {
        return view('livewire.faculty-admin-database');
    }
}
