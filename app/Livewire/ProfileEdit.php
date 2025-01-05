<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\RequestLog;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\study_program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileEdit extends Component
{
    public $user; // Property to hold user data
    public $name;
    public $username;
    public $faculty;
    public $currentFaculty;
    public $proposedRole;
    public $email;
    public $scholar;
    public $scopus;
    public $studyPrograms;
    public $roles;
    public int $selectedProgram;
    public $selectedRole;
    public $revision;
    public $message; // Property to hold success message

    public function mount($user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->faculty = $user->faculty->name;
        $this->currentFaculty = $user->faculty->id;
        $this->studyPrograms = study_program::where('faculty_id', $this->currentFaculty)->get();
        $this->roles = Role::whereIn('id', [2, 3, 4])->get(); // Fetch roles with IDs 2-4
        // $this->selectedProgram = $user->program_id;
        $this->selectedRole = $user->role->id;
        $this->proposedRole = $user->role;
        $this->scholar = $user->scholar;
        $this->scopus = $user->scopus;
        $this->revision = $user->revision;

        Log::info('Study Programs: ' . $this->studyPrograms);

    }

    public function sendRequest()
    {
        Log::info('sendRequest method called for user: ' . $this->user->id);
        Log::info('Selected Role: ' . $this->selectedRole);


            $this->validate([
                'selectedProgram' => 'required|exists:study_programs,id',
                'selectedRole' => 'required|exists:roles,id|not_in:1',
                'scopus' => 'required|unique:users',
                'scholar' => 'required|unique:users',
            ]);

            $role = $this->roles->where('id', $this->selectedRole)->first();

            Log::info('Proposed values:', [
                'user_id' => $this->user->id,
                'faculty_id' => $this->user->faculty->id,
                'program_id' => $this->selectedProgram,
                'requestrole_id' => $role->id,
                'action' => 'Request '. $role->name .' Role'
            ]);

            ActivityLog::create([
                'type' => 1,
                'user_id' => $this->user->id,
                'faculty_id' => $this->user->faculty->id,
                'program_id' => $this->selectedProgram,
                'requestrole_id' => $role->id,
                'action' => 'Request '. $role->name .' Role'
            ]);

            Log::info('Request Log Created for: ' . $this->user->id);

            $user = User::find($this->user->id);
            $user->program_id = $this->selectedProgram;
            $user->role_id = $this->selectedRole;
            $user->scholar = $this->scholar;
            $user->scopus = $this->scopus;
            $user->status = 2;
            $user->save(); // Save the updated user information

            Log::info('Updated values:', [
                'scopus' => $this->scopus,
                'scholar' => $this->scholar,
                'selectedProgram' => $this->selectedProgram,
                'selectedRole' => $this->selectedRole,
            ]);


            return redirect()->route('profile-edit', ['user' => Auth::user()->username])->with('message', 'Request Sucessfully Sent!');

    }

    public function deleteAccount()
    {
        User::find(Auth::user())->delete();

        return session()->flash('message', 'wrong email or password');
    }

    public function render()
    {
        return view('livewire.profile-edit', [
            'title' => 'Profile Edit - Manajemen Sitasi UNS'
        ]);
    }
}
