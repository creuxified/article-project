<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function landingPage()
    {
        return view('landing', ['title' => 'Sistem Manajemen Sitasi - Universitas Sebelas Maret']);
    }

    public function register()
    {
        return view('auth.register', ['title' => 'Register']);
    }

    public function login()
    {
        return view('auth.login', ['title' => 'Login']);
    }
}
