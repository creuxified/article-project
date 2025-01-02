<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class HistoryLogs extends Component
{
    public $logs;
    
    public function mount() // Initialize the component
    {
        if(Auth::user()->role->id == 3){
            $this->logs = ActivityLog::where('requestrole_id', 2)
            ->where('program_id', Auth::user()
            ->program->id)->with(['user', 'faculty', 'program', 'role'])
            ->get();
        }
        elseif(Auth::user()->role->id == 4){
            $this->logs = ActivityLog::whereIn('requestrole_id', [2, 3])
            ->where('faculty_id', Auth::user()->faculty->id)
            ->with(['user', 'faculty', 'program', 'role'])
            ->get();        
        }
        elseif(Auth::user()->role->id == 5){
            $this->logs = ActivityLog::whereIn('requestrole_id', [2, 3, 4])
            ->with(['user', 'faculty', 'program', 'role'])
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.history-logs');
    }
}
