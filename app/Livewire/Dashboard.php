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

    public function mount()
    {
        // Mendapatkan role_id pengguna yang sedang login
        $this->userRoleId = Auth::user()->role_id;

        if ($this->userRoleId == 2) {
            // Mendapatkan user_id pengguna yang sedang login
            $this->userId = Auth::user()->id;

             // Query 2: Menghitung total publikasi berdasarkan program_id pengguna
             $this->totalPublicationUsers = DB::table('publications as p')
             ->join('users as u', 'p.user_id', '=', 'u.id')
             ->where('u.id', $this->userId)
             ->count('p.user_id');

            // Query untuk menghitung total citation berdasarkan user_id
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.id', $this->userId) // Menggunakan $this->userId
                ->sum('p.citations');
        } elseif ($this->userRoleId == 3) {
            // Mendapatkan program_id pengguna yang sedang login
            $this->userProgramId = Auth::user()->program_id;

            // Query 1: Menghitung jumlah pengguna dengan program_id sesuai pengguna login
            $this->userCount = DB::table('users')
                ->where('program_id', $this->userProgramId)
                ->count();

            // Query 2: Menghitung total publikasi berdasarkan program_id pengguna
            $this->totalPublicationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.program_id', $this->userProgramId)
                ->count('p.user_id');

            // Query 3: Menghitung total sitasi dari publications
            $this->totalCitationUsers = DB::table('publications as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('u.program_id', $this->userProgramId)
                ->sum('p.citations');
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'userProgramId' => $this->userProgramId,
            'userCount' => $this->userCount,
            'totalPublicationUsers' => $this->totalPublicationUsers,
            'totalCitationUsers' => $this->totalCitationUsers,
        ]);
    }
}
