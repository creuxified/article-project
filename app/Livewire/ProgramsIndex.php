<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\ActivityLog;
use App\Models\study_program; // Pastikan nama model sesuai dengan konvensi
use Illuminate\Support\Facades\Auth;

class ProgramsIndex extends Component
{
    public $perPage = 10; // Jumlah item per halaman
    public $page = 1; // Halaman saat ini

    public function mount()
    {
        // Tidak perlu memuat program di sini
    }

    public function loadPrograms()
    {
        if (Auth::user()->role_id == 5) {
            return study_program::with('faculty')->paginate($this->perPage);
        } elseif (Auth::user()->role_id == 4) {
            return study_program::where('faculty_id', Auth::user()->faculty_id)
                ->with('faculty')->paginate($this->perPage);
        }
        return collect(); // Kembalikan koleksi kosong jika tidak ada kondisi yang terpenuhi
    }

    public function delete($id)
    {
        try {
            ActivityLog::where('program_id', $id)->delete();
            User::where('program_id', $id)->delete();
            study_program::findOrFail($id)->delete();
            session()->flash('message', 'Study Program deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the study program.');
        }
    }

    public function render()
    {
        $programs = $this->loadPrograms(); // Ambil data program dengan paginasi
        return view('livewire.programs-index', [
            'programs' => $programs, // Pass programs to the view
        ]);
    }
}
