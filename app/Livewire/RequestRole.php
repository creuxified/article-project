<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component; // Import the Livewire component
use App\Models\RequestLog; // Import the RequestLog model

class RequestRole extends Component
{
    public $logs; // Define the logs property
    public $userId;
    public $logId;
    public $isModalOpen = false;
    public $user;
    public $name;
    public $faculty;
    public $email;
    public $username;
    public $program;
    public $scholar;
    public $scopus;
    public $revision;

    public function mount() // Initialize the component
    {
        if(Auth::user()->role->id == 3){
            $this->logs = ActivityLog::where('type', 1)->where('program_id', Auth::user()->program->id)->where('requestrole_id', 2)->with(['user', 'faculty', 'program', 'role'])->get();
        }
        elseif(Auth::user()->role->id == 4){
            $this->logs = ActivityLog::where('type', 1)->where('faculty_id', Auth::user()->faculty->id)->where('requestrole_id', 3)->with(['user', 'faculty', 'program', 'role'])->get();
        }
        elseif(Auth::user()->role->id == 5){
            $this->logs = ActivityLog::where('type', 1)->where('requestrole_id', 4)->with(['user', 'faculty', 'program', 'role'])->get();
        }
    }

    public function openModal($user)
    {
        Log::info('sendRequest method called x user: ' . $user);
        $this->isModalOpen = true; // Open the modal
        Log::info('sendRequest method called for user: ' . $user);
    }

    public function render()
    {
        return view('livewire.request-role', ['logs' => $this->logs]); // Pass logs to the view
    }
}
