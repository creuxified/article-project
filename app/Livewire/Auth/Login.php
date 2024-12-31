<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $message;
    public $error;
    public String $email;
    public String $password;

    public function mount() {
        $this->message = session('message'); // Initialize message from session
        $this->error = session('error'); // Initialize message from session

    }

    public function login() {
        $this->validate([
            'email'=>'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt([
            'email' => $this->email, 
            'password' => $this->password
            ])){
            return Auth::user()->status == 4 ? redirect()->route('dashboard', ['user' => Auth::user()->username]) : redirect()->route('profile-edit', ['user' => Auth::user()->username]);            
            }else{
                return session()->flash('error', 'wrong email or password');
            }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
