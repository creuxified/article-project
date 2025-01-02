<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\ActivityLog;
use App\Models\study_program;
use Illuminate\Support\Facades\Auth;

class ProgramsIndex extends Component
{
    public $programs;

    public function mount()
    {
        if (Auth::user()->role_id = 5){
            $this->programs = study_program::with('faculty')->get();
        }
        elseif(Auth::user()->role_id = 4){
            $this->programs = study_program::where('faculty_id', Auth::user()->faculty_id)->with('faculty')->get();

        }
    }

    public function delete($id)
    {
        try {
            ActivityLog::where('program_id', $id)->delete();
            User::where('program_id', $id)->delete();
            study_program::findOrFail($id)->delete();
            session()->flash('message', 'Study Program deleted successfully!');
            return redirect()->route('program-index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the study program.');
            return redirect()->route('program-index');
        }
    }

    public function render()
    {
        return view('livewire.programs-index');
    }
}
