<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\HistoryLog;
use App\Models\study_program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAdd extends Component
{
    public $username, 
    $email, 
    $name, 
    $password, 
    $status = 1, 
    $role_id = 1, 
    $selectedFaculty, 
    $selectedProgram, 
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
            'faculty_id' => $this->selectedFaculty,
            'program_id' => $this->selectedProgram,
            'scholar' => $this->scholar,
            'scopus' => $this->scopus,
            'revision' => $this->revision,
        ]);

        session()->flash('message', 'User added successfully!');
        HistoryLog::create([
            'role_id' => Auth::user()->role->id,
            'faculty_id' => $this->user->faculty->id,
            'program_id' => $this->user->program_id,
            'activity' => Auth::user()->username. ' Created User '. $this->user->username,
        ]);
        
        $this->reset();

        return redirect()->route('user-database', ['user' => Auth::user()->username]);
    }

    public $showFaculty = false;
    public $showProgram = false;
    public $showScholar = false;
    public $showScopus = false;

    public function updatedSelectedRole($value)
    {
        $this->updateFieldVisibility();
        $this->dispatch('roleChanged', $value);
    }

    protected function updateFieldVisibility()
    {
        $this->showFaculty = in_array($this->selectedRole, [2, 3, 4]); // Dosen, Admin Prodi, Admin Fakultas
        $this->showProgram = in_array($this->selectedRole, [2, 3]); // Dosen, Admin Prodi
        $this->showScholar = ($this->selectedRole == 2); // Only Dosen
        $this->showScopus = ($this->selectedRole == 2); // Only Dosen

        // Reset program selection when faculty changes and program is not visible
        if (!$this->showProgram) {
            $this->selectedProgram = null;
        }
    }

    public function render()
    {
        $faculties = Faculty::all();
        $studyPrograms = study_program::all();

        return view('livewire.user-add', compact('faculties', 'studyPrograms'));
    }
}
