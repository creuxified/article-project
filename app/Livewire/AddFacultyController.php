<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class AddFacultyController extends Component
{
    public $name;

    // The render method renders the add-faculty view
    public function render()
    {
        return view('livewire.add-faculty');
    }

    // This method handles saving a new faculty
    public function saveFaculty()
    {
        // Validate the faculty name
        $this->validate([
            'name' => 'required|min:3',  // You can also add other validation rules here
        ]);

        try {
            // Create a new faculty entry and save it to the database
            $new_faculty = new Faculty;
            $new_faculty->name = $this->name;
            $new_faculty->save();

            // Set a flash message for success
            session()->flash('message', 'Faculty has been successfully added!');

            // Redirect to the faculty list page after saving
            return redirect()->route('faculty.index'); // Redirect to the list page with the flash message
        } catch (\Exception $e) {
            // For debugging purposes, you can log or display the error message
            dd($e->getMessage());
        }
    }
}