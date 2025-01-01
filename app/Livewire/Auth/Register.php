<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Faculty;
use Livewire\Component; 
use App\Models\RequestLog; 
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Integer;

class Register extends Component
{
    public $faculties;
    public $username;
    public $email;    
    public $name; 
    public int $selectedFaculty; 
    public $password; 
    public $password_confirmation; 

    public function mount() 
    {
        $this->faculties = Faculty::all(); 
    }

    public function register()
    {
        $this->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'username' => 'required|unique:users',
            'selectedFaculty' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        // Log::info('Proposed values:', [
        //     'username' => $this->username,
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'password' => bcrypt($this->password),
        //     'faculty_id' => $this->selectedFaculty,
        // ]);
        Log::info('Proposed values:', [
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'faculty_id' => $this->selectedFaculty,
        ]);

        User::create([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'faculty_id' => $this->selectedFaculty,
        ]);

        // return redirect('/login')->with('message', 'Registration successful! Please log in.');

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
