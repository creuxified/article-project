<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use App\Models\study_program;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserEdit extends Component
{
    public $user; // Property to hold user data
    public $name;
    public $username;
    public $faculties;
    public $selectedFaculty;
    public $email;
    public $scholar;
    public $scopus;
    public $studyPrograms = [];
    public $selectedProgram;
    public $selectedRole;
    public $message;
    public $status;

    protected $listeners = ['updatedSelectedFaculty' => 'updateStudyPrograms'];

    public function mount($user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->faculties = Faculty::all();
        $this->selectedFaculty = $user->faculty_id;
        $this->selectedProgram = $user->program_id;
        $this->selectedRole = $user->role_id;
        $this->scholar = $user->scholar ?? null;
        $this->scopus = $user->scopus ?? null;
        $this->status = $user->status;

        // Call the function to update field visibility
        $this->updateFieldVisibility();

        // Update the list of study programs based on the selected faculty
        $this->updateStudyPrograms();
    }

    public function updatedSelectedFaculty($value)
    {
        $this->selectedProgram = null;
        $this->updateStudyPrograms();
    }

    public function updateStudyPrograms()
    {
        if ($this->selectedFaculty) {
            $this->studyPrograms = study_program::where('faculty_id', $this->selectedFaculty)->get();
        } else {
            $this->studyPrograms = [];
        }
    }

    public function editProfile()
    {
        Log::info('edit profile function called', [
            'user_id' => $this->user->id,
            'input_data' => [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'selectedFaculty' => $this->selectedFaculty,
                'selectedProgram' => $this->selectedProgram,
                'selectedRole' => $this->selectedRole,
                'scholar' => $this->scholar,
                'scopus' => $this->scopus
            ]
        ]);

        // Initialize rules array
        $rules = [];

        // Handle guest role (role ID 1) separately
        if ($this->selectedRole == 1) {
            $this->selectedProgram = null;
            $this->scopus = null;
            $this->scholar = null;
            $this->status = 1; // Set status to 1 for Guest
        } else {
            if ($this->selectedRole == 2) {
                if ($this->selectedFaculty != $this->user->faculty_id) {
                    $rules['selectedFaculty'] = 'required|exists:faculties,id';
                }
                if ($this->selectedProgram != $this->user->program_id) {
                    $rules['selectedProgram'] = 'required|exists:study_programs,id,faculty_id,' . $this->selectedFaculty;
                }
                if ($this->scopus !== $this->user->scopus) {
                    $rules['scopus'] = 'nullable|unique:users,scopus,' . $this->user->scopus;
                }
                if ($this->scholar !== $this->user->scholar) {
                    $rules['scholar'] = 'nullable|unique:users,scholar,' . $this->user->scholar;
                }
                $this->status = 4; // Set status to 4 for Lecturer
            }

            // If the role is Program Admin (role ID 3), check faculty and program only
            if ($this->selectedRole == 3) {
                if ($this->selectedFaculty != $this->user->faculty_id) {
                    $rules['selectedFaculty'] = 'required|exists:faculties,id';
                }
                if ($this->selectedProgram != $this->user->program_id) {
                    $rules['selectedProgram'] = 'required|exists:study_programs,id,faculty_id,' . $this->selectedFaculty;
                }
                $this->status = 4; // Set status to 4 for Program Admin
            }

            // If the role is Faculty Admin (role ID 4), check only faculty and set program, scholar, and scopus to null
            if ($this->selectedRole == 4) {
                if ($this->selectedFaculty != $this->user->faculty_id) {
                    $rules['selectedFaculty'] = 'required|exists:faculties,id';
                }
                $this->selectedProgram = null;
                $this->scopus = null;
                $this->scholar = null;
                $this->status = 4; // Set status to 4 for Faculty Admin
            }
        }
            // If email is updated, add validation
            if ($this->email !== $this->user->email) {
                $rules['email'] = 'required|email|unique:users,email,' . $this->user->email;
            }

            // If name is updated, add validation
            if ($this->name !== $this->user->name) {
                $rules['name'] = 'required';
            }

            // If username is updated, add validation
            if ($this->username !== $this->user->username) {
                $rules['username'] = 'required|unique:users,username,' . $this->user->username;
            }

            // Role validation
            if ($this->selectedRole !== $this->user->role_id) {
                $rules['selectedRole'] = 'required|exists:roles,id';
            }

            // If the role is Lecturer (role ID 2), check the fields
            

        // If no rules are defined, there are no changes
    if (empty($rules)) {
        session()->flash('message', 'No changes were made.');
        return;
    }

    try {
        $validatedData = $this->validate($rules);

        Log::info('Validation successful', ['validated_data' => $validatedData]);

        // Process the validated data
        Log::info('try call saveuser');
        $this->saveUser($validatedData);
    } catch (\Exception $e) {
        Log::error('Error in editProfile', [
            'user_id' => $this->user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        session()->flash('error', 'An error occurred while updating the profile.');
    }
    }

    public function saveUser($validatedData)
{
    Log::info("SaveUser Called");

    $user = $this->user;

    // If the selectedRole is Guest, set status to 1, and clear other fields
    if ($this->selectedRole == 1) {
        $this->selectedProgram = null;  // Make program null
        $this->scopus = null;           // Make scopus null
        $this->scholar = null;          // Make scholar null
        $user->status = 1;              // Set status to Guest (1)
    }

    // Update user fields based on validated data
    if (isset($validatedData['email'])) $user->email = $this->email;
    if (isset($validatedData['name'])) $user->name = $this->name;
    if (isset($validatedData['username'])) $user->username = $this->username;
    if (isset($validatedData['selectedFaculty'])) $user->faculty_id = $this->selectedFaculty;
    if (isset($validatedData['selectedProgram'])) $user->program_id = $this->selectedProgram;
    if (isset($validatedData['scopus'])) $user->scopus = $this->scopus;
    if (isset($validatedData['scholar'])) $user->scholar = $this->scholar;
    if (isset($validatedData['selectedRole'])) $user->role_id = $this->selectedRole;

    // Save the updated user data
    $user->save();

    // Flash a success message to indicate successful update
    session()->flash('message', 'Information successfully updated.');
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
        $this->updateFieldVisibility();
        return view('livewire.user-edit');
    }
}