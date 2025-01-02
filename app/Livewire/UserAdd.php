<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\study_program;
use Illuminate\Support\Facades\Hash;

class UserAdd extends Component
{
    public $username, 
    $email, 
    $name, 
    $password, 
    $status = 1, 
    $role_id = 1, 
    $faculty_id, 
    $program_id, 
    $scholar, 
    $scopus, 
    $revision;

    protected $rules = [
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:8',
        'status' => 'required|integer',
        'role_id' => 'required|integer',
        'faculty_id' => 'nullable|integer',
        'program_id' => 'nullable|integer',
        'scholar' => 'nullable|string|max:255',
        'scopus' => 'nullable|string|max:255',
        'revision' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        $status = ($this->role_id == 1) ? 1 : 4;
        
        User::create([
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'status' => $status,
            'role_id' => $this->role_id,
            'faculty_id' => $this->faculty_id,
            'program_id' => $this->program_id,
            'scholar' => $this->scholar,
            'scopus' => $this->scopus,
            'revision' => $this->revision,
        ]);

        session()->flash('message', 'User added successfully!');
        $this->reset();
    }

    public function render()
    {
        $faculties = Faculty::all();
        $studyPrograms = study_program::all();

        return view('livewire.user-add', compact('faculties', 'studyPrograms'));
    }
}
