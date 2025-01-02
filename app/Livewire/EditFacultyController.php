<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class EditFacultyController extends Component
{
    public $faculty_id, $name;

    public function mount($faculty_id)
    {
        $this->faculty_id = $faculty_id;
        $faculty = Faculty::findOrFail($faculty_id);
        $this->name = $faculty->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
        ]);

        try {
            Faculty::where('id', $this->faculty_id)->update([
                'name' => $this->name,
            ]);
            return redirect()->route('faculty.index'); // Redirect after update
        } catch (\Exception $e) {
            dd($e->getMessage()); // For debugging purposes
        }
    }

    public function render()
    {
        return view('livewire.edit-faculty');
    }
}
