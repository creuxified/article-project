<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Faculty;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarScraperController;
use App\Http\Controllers\ScopusScraperController;

Route::get('/', function () {
    return view('landing', ['title' => 'Landing Page']);
})->name('landing');

Route::get('/login', function () {
    return view('auth.login', ['title' => 'Login']);
})->name('login');

Route::get('/dashboard/{user:username}', function (User $user) {
    return view('dashboard', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('dashboard');

Route::get('/request-role/{user:username}', function (User $user) {
    $logs = ActivityLog::with(['user', 'faculty', 'program'])->get();
    return view('request-role', ['user' => $user, 'logs' => $logs, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('request-role');

Route::get('/user-database/{user:username}', function (User $user) {
    return view('user-database', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('user-database');

Route::get('/register', function () {
    $faculties = Faculty::all();
    return view('auth.register', ['faculties' => $faculties, 'title' => 'register']);
})->name('register');

Route::get('/profile-edit/{user:username}', function (User $user) {
    return view('profile-edit', ['user' => $user, 'title' => 'Welcome to profile editor!']);
})->middleware('auth')->name('profile-edit');

Route::get('/user-profile-edit/{user:username}', function (User $user) {
    return view('user-profile-edit', ['user' => $user, 'title' => 'Welcome '. $user->name .'!']);
})->middleware('auth')->name('user-profile-edit');

Route::get('/scrap-data/{user:username}', function (User $user) {
    return view('scrap-data', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('scrap-data');

Route::post('logout', function (Request $request): RedirectResponse {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/scrap/scholar', [ScholarScraperController::class, 'index'])->name('scrape');

// Route::get('/scrap/scopus', [ScopusScraperController::class, 'showForm']); // For displaying the form
// Route::post('/scrap/scopus', [ScopusScraperController::class, 'scrapeScopus']); // For handling the form submission

Route::middleware('auth')->group(function () {});

Route::get('/scrap/scopus', [ScopusScraperController::class, 'showForm']);
Route::post('/scrap/scopus', [ScopusScraperController::class, 'scrapeScopus']);

use App\Livewire\FacultyController;

Route::get('/faculty', FacultyController::class)->name('faculty.index');

use App\Livewire\EditFacultyController;

Route::get('/faculty/edit/{faculty_id}', EditFacultyController::class)->name('faculty.edit');

use App\Livewire\AddFacultyController;
Route::get('/add/new',AddFacultyController::class);

use App\Livewire\AddStudyProgram;

Route::get('/study-program/add', AddStudyProgram::class)->name('study-program.add');

use App\Livewire\ListStudyProgramController;
Route::get('/study-program', ListStudyProgramController::class)->name('study-program.list');

use App\Livewire\EditStudyProgramController;

// Rute untuk mengedit program studi
Route::get('/study-program/edit/{id}', EditStudyProgramController::class)->name('study-program.edit');

use App\Livewire\AddUserController;

Route::get('/users/add', AddUserController::class)->name('users.add');

use App\Livewire\ListUserController;

Route::get('/users', ListUserController::class)->name('users.list');

use App\Livewire\EditUserController;

Route::get('/users/edit/{id}', EditUserController::class)->name('users.edit');
