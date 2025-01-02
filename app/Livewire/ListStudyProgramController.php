<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;

class ListStudyProgramController extends Component
{
    public $study_programs;

    public function mount()
    {
        $this->loadStudyPrograms();
    }

    public function loadStudyPrograms()
    {
        $this->study_programs = StudyProgram::with('faculty')->get();
    }

    public function delete($id)
    {
        try {
            StudyProgram::findOrFail($id)->delete();
            session()->flash('message', 'Study Program deleted successfully!');
            $this->loadStudyPrograms(); // Refresh data setelah penghapusan
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the study program.');
        }
    }

    public function render()
    {
        return view('livewire.list-study-program');
    }
}
