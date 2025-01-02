<?php

namespace App\Livewire;

use App\Models\Faculty;
use Livewire\Component;

class FacultyAdd extends Component
{
    public $name;

    public function render()
    {
        return view('livewire.faculty-add');
    }

    public function saveFaculty()
    {
        // Validate the faculty name
        $this->validate([
            'name' => 'required|min:3|unique:faculties,name',  // You can also add other validation rules here
        ]);

        try {
            // Create a new faculty entry and save it to the database
            $new_faculty = new Faculty;
            $new_faculty->name = $this->name;
            $new_faculty->save();

            // Set a flash message for success
            session()->flash('message', 'Faculty has been successfully added!');

            // Redirect to the faculty list page after saving
            return redirect()->route('faculty-index'); // Redirect to the list page with the flash message
        } catch (\Exception $e) {
            // For debugging purposes, you can log or display the error message
            dd($e->getMessage());
        }
    }
}
