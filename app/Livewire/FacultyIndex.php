<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Faculty;
use Livewire\Component;
use App\Models\HistoryLog;
use App\Models\ActivityLog;
use App\Models\study_program;
use Illuminate\Support\Facades\Auth;

class FacultyIndex extends Component
{
    public $faculties;

    public function mount()
    {
        $this->faculties = Faculty::all(); // You can consider pagination if needed
    }

    public function delete($id)
    {
        try {
            HistoryLog::create([
                'role_id' => Auth::user()->role->id,
                'faculty_id' => null,
                'program_id' => null,
                'activity' => Auth::user()->username.' deleted ['. Faculty::find($id)->name . '] Faculty',
            ]);
            ActivityLog::where('faculty_id', $id)->delete();
            HistoryLog::where('faculty_id', $id)->delete();
            User::where('faculty_id', $id)->delete();
            study_program::where('faculty_id', $id)->delete();
            Faculty::where('id', $id)->delete();
            session()->flash('message', 'Faculty deleted successfully!');

            return redirect()->route('faculty-index');
        } catch (\Exception $th) {
            session()->flash('error', 'An error occurred while deleting the faculty.');
            return redirect()->route('faculty-index');
        }
    }

    public function render()
    {
        return view('livewire.faculty-index');
    }
}
