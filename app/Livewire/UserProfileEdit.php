<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\study_program;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserProfileEdit extends Component
{   
    public $user; // Property to hold user data
    public $name;
    public $username;
    public $faculties;
    public $selectedFaculty;
    public $email;
    public $scholar;
    public $scopus;
    public $studyPrograms;
    public int $selectedProgram;
    public $message; 

    public function mount($user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->faculties = Faculty::all();
        $this->studyPrograms = study_program::where('faculty_id', Auth::user()->faculty_id)->get();
        $this->scholar = $user->scholar;
        $this->scopus = $user->scopus;
        if ($user->role_id != 5){
            $this->selectedFaculty = $user->faculty_id;
            if ($user->role_id != 4) 
            $this->selectedProgram = $user->program_id;
        }
        

        Log::info('Study Programs: ' . $this->studyPrograms);

    }
    
    public function render()
    {
        return view('livewire.user-profile-edit');
    }

    public function editProfile(){
        Log::info('edit profile function called');

        $validatedData = [];

        // Only add validation rules for fields that have been modified
        if ($this->email !== $this->user->email) {
            $validatedData['email'] = 'required|email|unique:users,email,' . $this->user->id;
        }
    
        if ($this->name !== $this->user->name) {
            $validatedData['name'] = 'required';
        }
    
        if ($this->username !== $this->user->username) {
            $validatedData['username'] = 'required|unique:users,username,' . $this->user->id;
        }
        if ($this->user->role_id != 5){
            if ($this->selectedFaculty != $this->user->faculty_id) {
                $validatedData['selectedFaculty'] = 'required|exists:faculties,id';
            }
            if ($this->user->role_id != 4){
                if ($this->selectedProgram != $this->user->program_id) {
                    $validatedData['selectedProgram'] = 'required|exists:study_programs,id,faculty_id,' . $this->selectedFaculty;
                }
            
            }
        }

        if ($this->scopus !== $this->user->scopus) {
            $validatedData['scopus'] = 'nullable|unique:users,scopus,' . $this->user->id;
        }
    
        if ($this->scholar !== $this->user->scholar) {
            $validatedData['scholar'] = 'nullable|unique:users,scholar,' . $this->user->id;
        }
    
        // Validate only the fields that have rules
        $this->validate($validatedData);
    
        // Update fields only if they have been changed
        $user = $this->user;
        if (isset($validatedData['email'])) $user->email = $this->email;
        if (isset($validatedData['name'])) $user->name = $this->name;
        if (isset($validatedData['username'])) $user->username = $this->username;
        if (isset($validatedData['selectedFaculty'])) $user->faculty_id = $this->selectedFaculty;
        if (isset($validatedData['selectedProgram'])) $user->program_id = $this->selectedProgram;
        if (isset($validatedData['scopus'])) $user->scopus = $this->scopus;
        if (isset($validatedData['scholar'])) $user->scholar = $this->scholar;
    
        $user->updated_at = now();
        $user->save();
    
        return redirect()->route('user-profile-edit', ['user' => $user->username])
            ->with('message', 'Information Successfully Edited!');
    }
}
