<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Auth;

class HistoryLogs extends Component
{
    public $logs;
    
    public function mount() // Initialize the component
    {
        if(Auth::user()->role->id == 3){
            $this->logs = HistoryLog::whereIn('role_id', [2, 3])
            ->where('program_id', Auth::user()
            ->program->id)->with([ 'faculty', 'program', 'role'])
            ->get();
        }
        elseif(Auth::user()->role->id == 4){
            $this->logs = HistoryLog::whereIn('role_id', [2, 3, 4])
            ->where('faculty_id', Auth::user()->faculty->id)
            ->with(['faculty', 'program', 'role'])
            ->get();        
        }
        elseif(Auth::user()->role->id == 5){
            $this->logs = HistoryLog::whereIn('role_id', [2, 3, 4,5])
            ->with(['faculty', 'program', 'role'])
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.history-logs');
    }
}
