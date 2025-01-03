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

Route::middleware('auth')->group(function () {});

Route::get('/scrap/scopus', [ScopusScraperController::class, 'showForm']);
Route::post('/scrap/scopus', [ScopusScraperController::class, 'scrapeScopus']);

// FACULTY ZONE
Route::get('/faculty', function (User $user) {
    return view('faculty-index', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('faculty-index');

Route::get('/faculty/edit/{id}', function ($id) {
    return view('faculty-edit', [
        'title' => 'Faculty Edit',
        'id' => $id,
    ]);
})->middleware('auth')->name('faculty-edit');

Route::get('/faculty/add/', function (User $user) {
    return view('faculty-add', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('faculty-add');

//PROGRAMS ZONE
Route::get('/programs', function (User $user) {
    return view('programs-index', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('programs-index');

Route::get('/programs/add/', function (User $user) {
    return view('programs-add', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('programs-add');


Route::get('/programs/edit/{id}', function ($id) {
    return view('programs-edit', [
        'title' => 'programs Edit',
        'id' => $id,
    ]);
})->middleware('auth')->name('programs-edit');

//USER ZONE
Route::get('/users/add/', function (User $user) {
    return view('user-add', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('users-add');

Route::get('/users/edit/{user}', function (User $user) {
    return view('user-edit', [
        'title' => 'Edit User: ' . $user->name,
        'user' => $user
    ]);
})->middleware('auth')->name('user.edit');

// use App\Livewire\EditUserController;

// Route::get('/users/edit/{id}', EditUserController::class)->name('users.edit');

Route::get('/scopus', [ScopusScraperController::class, 'index'])->name('scopus.index');
Route::post('/scopus/fetch', [ScopusScraperController::class, 'fetchData'])->name('scopus.fetch');

use App\Http\Controllers\ScopusController;

Route::middleware(['auth'])->group(function () {
    // Route untuk menampilkan data publikasi
    Route::get('/scopus', [ScopusController::class, 'showPublications'])->name('scopus.index');

    // Route untuk menangani form submission dan scraping
    Route::post('/scopus/scrape', [ScopusController::class, 'scrapeAndShow'])->name('scopus.scrape');
});

use App\Http\Controllers\ScholarController;

// Route::middleware(['auth'])->group(function () {
//     // Menampilkan publikasi dari Google Scholar
    // Route::get('/scholar', [ScholarController::class, 'index'])->name('scholar.index');

    // Route::post('/scholar/scrape', [ScholarController::class, 'scrapeAndShow'])->name('scholar.scrape');
// });


// Route::get('/scholar', [ScholarController::class, 'index'])->name('scrape');

Route::get('/scholar', [ScholarController::class, 'showPublications'])->name('scholar.index');
Route::post('/scholar/scrape', [ScholarController::class, 'scrapeAndShow'])->name('scholar.scrape');
Route::delete('/scholar/deleteData', [ScholarController::class, 'deleteData'])->name('scholar.deleteData');


Route::get('/scholar', [ScholarController::class, 'showPublications'])->name('scholar.index');
Route::post('/scholar/scrape', [ScholarController::class, 'scrapeAndShow'])->name('scholar.scrape');
Route::delete('/scholar/deleteData', [ScholarController::class, 'deleteData'])->name('scholar.deleteData');

use App\Http\Controllers\ScraperController;
Route::get('/scraper', [ScraperController::class, 'showPublications'])->name('scraper.index'); // Menampilkan halaman publikasi
Route::post('/scraper/scrape', [ScraperController::class, 'scrapeAndShow'])->name('scraper.scrape'); // Menangani scraping data
Route::delete('/scraper/delete', [ScraperController::class, 'deleteData'])->name('scraper.deleteData'); // Menangani penghapusan data
