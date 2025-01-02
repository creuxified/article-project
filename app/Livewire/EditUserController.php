<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class EditUserController extends Component
{
    public $userId;
    public $username;
    public $email;
    public $name;
    public $password;
    public $status;
    public $role_id;
    public $faculty_id;
    public $program_id;
    public $scholar;
    public $scopus;
    public $revision;
    public $faculties;
    public $studyPrograms;

    public function mount($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->password = ''; // Ensure the password is empty by default
        $this->status = $user->status;
        $this->role_id = $user->role_id;
        $this->faculty_id = $user->faculty_id;
        $this->program_id = $user->program_id;
        $this->scholar = $user->scholar;
        $this->scopus = $user->scopus;
        $this->revision = $user->revision;

        // Fetch faculties and study programs
        $this->faculties = Faculty::all();
        $this->studyPrograms = StudyProgram::all();
    }

    public function update()
    {
        $this->validate([
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'name' => 'required|max:255',
            'password' => 'nullable|min:6', // Password is optional
            'status' => 'required|in:0,1',
            'role_id' => 'nullable|exists:roles,id', // Validate role_id if available
            'faculty_id' => 'nullable|exists:faculties,id', // Validate faculty_id if available
            'program_id' => 'nullable|exists:study_programs,id', // Validate program_id if available
        ]);

        $user = User::find($this->userId);

        if ($user) {
            $user->username = $this->username;
            $user->email = $this->email;
            $user->name = $this->name;

            // Update password only if it is provided
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }

            $user->status = $this->status;
            $user->role_id = $this->role_id;
            $user->faculty_id = $this->faculty_id;
            $user->program_id = $this->program_id;
            $user->scholar = $this->scholar;
            $user->scopus = $this->scopus;
            $user->revision = $this->revision;

            $user->save();

            session()->flash('message', 'User updated successfully!');
        } else {
            session()->flash('error', 'User not found!');
        }
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
