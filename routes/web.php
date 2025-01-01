<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Faculty;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Scraper;

Route::get('/', function () {
    return view('landing', ['title' => 'Landing Page']);
})->name('landing');

Route::get('/login', function () {
    return view('auth.login', ['title' => 'Login']);
})->name('login');

Route::get('/dashboard/{user:username}', function (User $user) {
    return view('dashboard', ['user' => $user, 'title' => 'Welcome '. $user->name .'!']);
})->middleware('auth')->name('dashboard');

Route::get('/request-role/{user:username}', function (User $user) {
    $logs = ActivityLog::with(['user', 'faculty', 'program'])->get();
    return view('request-role', ['user' => $user,'logs'=> $logs,'title' => 'Welcome '. $user->name .'!']);
})->middleware('auth')->name('request-role');

Route::get('/register', function () {
    $faculties = Faculty::all();
    return view('auth.register', ['faculties'=> $faculties, 'title' => 'register']);
})->name('register');

Route::get('/profile-edit/{user:username}', function (User $user) {
    return view('profile-edit', ['user' => $user, 'title' => 'Welcome to profile editor!']);
})->middleware('auth')->name('profile-edit');

Route::post('logout', function (Request $request): RedirectResponse {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/scrap', [Scraper::class, 'index'])->name('scrape');
