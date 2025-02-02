<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\HistoryLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ModalRole extends Component
{
    public $isModalOpen = false; // Initialize the modal state
    public $user;
    public $log;
    public $name;
    public $faculty;
    public $email;
    public $username;
    public $program;
    public $scholar;
    public $scopus;
    public $revision;
    public $userId; // Define userId property
    public $logId;  // Define logId property

    public function mount($user)
    {
        $this->name = $user->name;
        $this->revision = $user->revision;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->faculty = $user->faculty->name;
        $this->program = $user->program->name;
        $this->scholar = $user->scholar;
        $this->scopus = $user->scopus;
        $this->isModalOpen = true; // Open the modal

    }

    public function accept()
    {
        $user = $this->user;
        $user->status = 4;
        $user->save();
        Log::info('accept method called for user: ' . $this->user->name);

        $log = $this->log;
        $log->is_reviewed = true;
        $log->save();

        Log::info('accept method called for log: ' . $this->log->action);

        HistoryLog::create([
            'role_id' => Auth::user()->role->id,
            'faculty_id' => $log->user->faculty->id,
            'program_id' => $log->program->id,
            'activity' => Auth::user()->username. ' gave role '. $user->username . ': [' . $log->action. ']',
        ]);

        return redirect()->route('request-role', ['user' => Auth::user()->username])->with('message', 'User succesfully accepted!');
    }

    public function reject()
    {
        $user = $this->user;
        $user->status = 3;
        $user->revision = $this->revision;
        $user->save();
        Log::info('reject method called for user: ' . $this->user->name);

        $log = $this->log;
        $log->is_reviewed = true;
        $log->save();

        Log::info('reject method called for log: ' . $this->log->action);

        HistoryLog::create([
            'role_id' => Auth::user()->role->id,
            'faculty_id' => $log->user->faculty->id,
            'program_id' => $log->program->id,
            'activity' => Auth::user()->username. ' rejected '. $user->username . ': [' . $log->action. ']',
        ]);

        Log::info('Reject ActivityLog created for : ' . Auth::user()->username. ' toward ' . $user->username);
        
        return redirect()->route('request-role', ['user' => Auth::user()->username])->with('message', 'User succesfully rejected!');
    }

    public function render()
    {
        return view('livewire.modal-role');
    }
}
