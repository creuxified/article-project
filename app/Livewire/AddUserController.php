<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AddUserController extends Component
{
    public $username, $email, $name, $password, $status = 1, $role_id = 1, $faculty_id, $program_id, $scholar, $scopus, $revision;

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

        User::create([
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'status' => $this->status,
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
        // Get faculties and study programs
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();

        return view('livewire.add-user', compact('faculties', 'studyPrograms'));
    }
}
