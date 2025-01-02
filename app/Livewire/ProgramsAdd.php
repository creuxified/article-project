<?php

namespace App\Livewire;

use App\Models\Faculty;
use App\Models\study_program;
use Livewire\Component;

class ProgramsAdd extends Component
{
    public $faculties;
    public $name;
    public $selectedFaculty;

    public function mount()
    {
        $this->faculties = Faculty::all();

    }
    public function render()
    {
        return view('livewire.programs-add');
    }

    public function saveStudyProgram()
    {
        $this->validate([
            'name' => 'required|min:3|unique:study_programs,name',
            'selectedFaculty' => 'required|exists:faculties,id', 
        ]);

        try {
            $studyProgram = new study_program();
            $studyProgram->name = $this->name;
            $studyProgram->faculty_id = $this->selectedFaculty;
            $studyProgram->save();

            session()->flash('message', 'Study Program has been successfully added!');

            return redirect()->route('programs-index'); // Redirect to the list page with the flash message
        } catch (\Exception $e) {
            // Log atau tampilkan error
            dd($e->getMessage());
        }
    }
}
