<?php

namespace App\Livewire;

use App\Models\Faculty;
use Livewire\Component;

class FacultyEdit extends Component
{
    public $faculty;
    public $name;
    
    protected $rules = [
        'name' => 'required',
    ];

    public function mount($id)
    {
        $this->faculty = Faculty::where('id', $id)->first();
        $this->name = $this->faculty->name;
    }

    public function update()
    {
        $additionalRules = [];
        
        if ($this->name !== $this->faculty->name) {
            $additionalRules['name'] = 'unique:faculties,name,' . $this->faculty->name;
        }

        $this->validate(array_merge($this->rules, $additionalRules));

        try {
            Faculty::where('id', $this->faculty->id)->update([
                'name' => $this->name,
            ]);
            return redirect()->route('faculty-index'); // Redirect after update
        } catch (\Exception $e) {
            dd($e->getMessage()); // For debugging purposes
        }
    }


    public function render()
    {
        return view('livewire.faculty-edit');
    }
}
