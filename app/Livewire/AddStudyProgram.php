<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudyProgram;

class AddStudyProgram extends Component
{
    public $name, $faculty_id;

    public function render()
    {
        // Ambil data fakultas untuk dropdown (jika diperlukan)
        $faculties = \App\Models\Faculty::all();

        return view('livewire.add-study-program', compact('faculties'));
    }

    public function saveStudyProgram()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|min:3',
            'faculty_id' => 'required|exists:faculties,id', // Pastikan faculty_id valid
        ]);

        try {
            // Simpan data Study Program ke database
            $studyProgram = new StudyProgram;
            $studyProgram->name = $this->name;
            $studyProgram->faculty_id = $this->faculty_id;
            $studyProgram->save();

            // Flash message sukses
            session()->flash('message', 'Study Program has been successfully added!');

            // Redirect ke halaman daftar Study Program
            return redirect()->route('study-program.index');
        } catch (\Exception $e) {
            // Log atau tampilkan error
            dd($e->getMessage());
        }
    }
}
