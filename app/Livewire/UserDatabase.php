<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserDatabase extends Component
{
    public $isModalOpen = false;
    public $userRole;

    public function mount()
    {
        $this->userRole = Auth::user()->role_id;
    }
    
    public function render()
    {
        return view('livewire.user-database', [
            'userRole' => $this->userRole
        ]);
    }
}
