<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Faculty;

class FacultyController extends Component
{
    public $faculties;

    public function mount()
    {
        $this->faculties = Faculty::all(); // You can consider pagination if needed
    }

    public function delete($id)
    {
        try {
            Faculty::where('id', $id)->delete();
            session()->flash('message', 'Faculty deleted successfully!');
            return view('livewire.faculty');
        } catch (\Exception $th) {
            session()->flash('error', 'An error occurred while deleting the faculty.');
            return view('livewire.add-faculty');
        }
    }

    public function render()
    {
        return view('livewire.faculty', [
            'faculty' => $this->faculties
        ]);
    }
}
