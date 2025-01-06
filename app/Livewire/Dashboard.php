<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $userCount;
    public $totalPublicationUsers;
    public $totalCitationUsers;
    public $userProgramId;
    public $userRoleId;
    public $userId;

    public $totalLecturer;
    public $totalStudyProgram;
    public $totalFaculty; // Variabel untuk total fakultas
    public $publications; // Variabel untuk menyimpan hasil publikasi

    public $faculties;
    public $study_programs;

    public function mount()
    {
        // Mendapatkan role_id pengguna yang sedang login
        $this->userRoleId = Auth::user()->role_id;

        $this->faculties = DB::table('faculties')->get();
        $this->study_programs = DB::table('study_programs')->get();

        // Mengambil publikasi dari database
        $this->publications = DB::table('publications as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('study_programs as sp', 'u.program_id', '=', 'sp.id')
            ->join('faculties as f', 'u.faculty_id', '=', 'f.id')
            ->select('u.name as lecturer', 'sp.id as study_program_id', 'sp.name as study_program', 'f.id as faculty_id', 'f.name as faculty', 'p.*')
            ->get();

        // Jika role_id pengguna adalah 2 (Dosen)
        if ($this->userRoleId == 2) {
            // Mendapatkan user_id pengguna yang sedang login
            $this->userId = Auth::user()->id;

            // Query untuk menghitung total publikasi berdasarkan user_id
            $this->totalPublicationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.id', $this->userId)
                ->count('p.user_id');

            // Query untuk menghitung total citation berdasarkan user_id
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.id', $this->userId)
                ->sum('p.citations');
        }
        // Jika role_id pengguna adalah 3 (Mahasiswa)
        elseif ($this->userRoleId == 3) {
            // Mendapatkan program_id pengguna yang sedang login
            $this->userProgramId = Auth::user()->program_id;

            // Query untuk menghitung jumlah pengguna berdasarkan program_id
            $this->userCount = DB::table('users')
                ->where('program_id', $this->userProgramId)
                ->count();

            // Query untuk menghitung total publikasi berdasarkan program_id
            $this->totalPublicationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.program_id', $this->userProgramId)
                ->count('p.user_id');

            // Query untuk menghitung total citation berdasarkan program_id
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.program_id', $this->userProgramId)
                ->sum('p.citations');
        }
        // Jika role_id pengguna adalah 4 (Admin Fakultas)
        elseif ($this->userRoleId == 4) {
            // Mendapatkan faculty_id pengguna yang sedang login
            $this->userProgramId = Auth::user()->faculty_id;

            // Query untuk menghitung jumlah dosen berdasarkan faculty_id
            $this->totalLecturer = DB::table('users')
                ->where('faculty_id', $this->userProgramId)
                ->where('role_id', 2) // Role_id untuk Dosen
                ->count();

            // Query untuk menghitung jumlah mahasiswa berdasarkan faculty_id
            $this->totalStudyProgram = DB::table('users')
                ->where('faculty_id', $this->userProgramId)
                ->where('role_id', 3) // Role_id untuk Mahasiswa
                ->count();

            // Query untuk menghitung total publikasi berdasarkan faculty_id
            $this->totalPublicationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.faculty_id', $this->userProgramId) // Menggunakan faculty_id untuk admin fakultas
                ->count('p.user_id');

            // Query untuk menghitung total citation berdasarkan faculty_id
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.faculty_id', $this->userProgramId) // Menggunakan faculty_id untuk admin fakultas
                ->sum('p.citations');
        }
        // Jika role_id pengguna adalah 5 (Admin Universitas)
        elseif ($this->userRoleId == 5) {
            // Admin Universitas bisa melihat semua data, tanpa filter faculty_id atau program_id

            // Query untuk menghitung total fakultas
            $this->totalFaculty = DB::table('faculties')->count();

            // Query untuk menghitung total dosen (role_id == 2) di seluruh fakultas
            $this->totalLecturer = DB::table('users')
                ->where('role_id', 2) // Role_id untuk Dosen
                ->count();

            // Query untuk menghitung total mahasiswa (role_id == 3) di seluruh fakultas
            $this->totalStudyProgram = DB::table('users')
                ->where('role_id', 3) // Role_id untuk Mahasiswa
                ->count();

            // Query untuk menghitung total publikasi di seluruh fakultas
            $this->totalPublicationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->count('p.user_id');

            // Query untuk menghitung total citation di seluruh fakultas
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->sum('p.citations');
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'userProgramId' => $this->userProgramId,
            'userCount' => $this->userCount,
            'totalLecturer' => $this->totalLecturer,
            'totalStudyProgram' => $this->totalStudyProgram,
            'totalFaculty' => $this->totalFaculty, // Menambahkan total fakultas untuk admin universitas
            'totalPublicationUsers' => $this->totalPublicationUsers,
            'totalCitationUsers' => $this->totalCitationUsers,
            'publications' => $this->publications,
            'faculties' => $this->faculties,
            'study_programs' => $this->study_programs,
        ]);
    }
}
