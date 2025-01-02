<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;
use App\Models\Faculty;

class EditStudyProgramController extends Component
{
    public $studyProgramId;
    public $name;
    public $faculty_id;
    public $faculties;

    public function mount($id)
    {
        $studyProgram = StudyProgram::findOrFail($id);
        $this->studyProgramId = $studyProgram->id;
        $this->name = $studyProgram->name;
        $this->faculty_id = $studyProgram->faculty_id;
        $this->faculties = Faculty::all();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $studyProgram = StudyProgram::findOrFail($this->studyProgramId);
        $studyProgram->update([
            'name' => $this->name,
            'faculty_id' => $this->faculty_id,
        ]);

        session()->flash('message', 'Study Program updated successfully!');
        return redirect()->route('study-program.list');
    }

    public function render()
    {
        return view('livewire.edit-study-program');
    }
}
