<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    // Di dalam Komponen Livewire
    public $title = 'Citation Management System'; // Atau bisa menggunakan nilai default

    public function render()
    {
        // return view('livewire.landing-page', ['title' => 'Citation Management System']);
        return view('livewire.landing-page');
    }
}
