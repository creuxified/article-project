<?php

namespace App\Livewire;

use App\Models\Faculty;
use Livewire\Component;
use App\Models\study_program;

class ProgramsEdit extends Component
{
    public $program;
    public $name;
    public $selectedFaculty;
    public $faculties;

    protected $rules = [
        'name' => 'required',
    ];

    public function mount($id)
    {
        $this->faculties = Faculty::all();
        $this->program = study_program::where('id', $id)->first();
        $this->name = $this->program->name;
        $this->selectedFaculty = $this->program->faculty_id;
    }

    public function update()
    {
        $additionalRules = [];

        if ($this->name !== $this->program->name) {
            $additionalRules['name'] = 'unique:study_programs,name,' . $this->program->name;
        }


        $this->validate(array_merge($this->rules, $additionalRules));

        try {
            study_program::where('id', $this->program->id)->update([
                'name' => $this->name,
                'faculty_id' => $this->selectedFaculty,
            ]);
            return redirect()->route('programs-index'); // Redirect after update
        } catch (\Exception $e) {
            dd($e->getMessage()); // For debugging purposes
        }

    }

    public function render()
    {
        return view('livewire.programs-edit');
    }
}
