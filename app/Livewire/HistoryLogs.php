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
        $this->logs = ActivityLog::where('program_id', Auth::user()->program->id)->with(['user', 'faculty', 'program'])->get();
    }

    public function render()
    {
        return view('livewire.history-logs');
    }
}
