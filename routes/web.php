<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Faculty;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\ScopusController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\TestScraperController;

use App\Http\Controllers\AuthController;

// ==================== NO AUTHENTICATED ROUTES (Guest) ====================
// Route::get('/', [AuthController::class, 'landingPage'])->name('landing');
// Route::get('/register', [AuthController::class, 'register'])->name('register');
// Route::get('/login', [AuthController::class, 'login'])->name('login');

use App\Livewire\LandingPage;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Login;

// ==================== NO AUTHENTICATED ROUTES (Guest) ====================
Route::get('/', LandingPage::class)->name('landing');
// Route::get('/register', Register::class)->name('register');
// Route::get('/login', Login::class)->name('login');



// Route::get('/', function () {
//     return view('landing', ['title' => 'Landing Page']);
// })->name('landing');

Route::get('/register', function () {
    // $faculties = Faculty::all();
    // return view('auth.register', ['faculties' => $faculties, 'title' => 'register']);
    return view('auth.register', ['title' => 'register']);
})->name('register');

Route::get('/login', function () {
    return view('auth.login', ['title' => 'Login']);
})->name('login');




// ==================== LOGOUT ROUTES ====================
Route::post('logout', function (Request $request): RedirectResponse {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/test/scraper', [TestScraperController::class, 'showPublications'])->name('test-scraper.index'); // View scraper
    Route::post('/test/scraper/scrape', [TestScraperController::class, 'scrapeAndShow'])->name('test-scraper.scrape'); // Start scraping
    Route::delete('/test/scraper/delete', [TestScraperController::class, 'deleteData'])->name('test-scraper.deleteData'); // Delete scraper data

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // SCRAPER ROUTES
    Route::get('/scraper', [ScraperController::class, 'showPublications'])->name('scraper.index'); // View scraper
    Route::post('/scraper/scrape', [ScraperController::class, 'scrapeAndShow'])->name('scraper.scrape'); // Start scraping
    Route::delete('/scraper/delete', [ScraperController::class, 'deleteData'])->name('scraper.deleteData'); // Delete scraper data

    // SCHOLAR ROUTES
    Route::get('/scholar', [ScholarController::class, 'showPublications'])->name('scholar.index'); // View Scholar data
    Route::post('/scholar/scrape', [ScholarController::class, 'scrapeAndShow'])->name('scholar.scrape'); // Start scraping Scholar
    Route::delete('/scholar/deleteData', [ScholarController::class, 'deleteData'])->name('scholar.deleteData'); // Delete Scholar data

    // SCOPUS ROUTES
    Route::get('/scopus', [ScopusController::class, 'showPublications'])->name('scopus.index'); // View Scopus data
    Route::post('/scopus/scrape', [ScopusController::class, 'scrapeAndShow'])->name('scopus.scrape'); // Start scraping Scopus
    Route::delete('/scopus/deleteData', [ScopusController::class, 'deleteData'])->name('scopus.deleteData'); // Delete Scopus data

    // GUEST ROUTES

    // DOSEN ROUTES

    // PROGRAM STUDY ADMIN ROUTES

    // FACULTY ADMIN ROUTES

    // UNIVERSITY ADMIN ROUTES
});




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


// Route::get('/dashboard/{user:username}', [DashboardController::class, 'show'])->middleware('auth')->name('dashboard');
// Route::get('/request-role/{user:username}', [RoleRequestController::class, 'show'])->middleware('auth')->name('request-role');
// Route::get('/user-database/{user:username}', [UserDatabaseController::class, 'show'])->middleware('auth')->name('user-database');

Route::get('/profile-edit/{user:username}', function (User $user) {
    return view('profile-edit', ['user' => $user, 'title' => 'Welcome to profile editor!']);
})->middleware('auth')->name('profile-edit');

Route::get('/user-profile-edit/{user:username}', function (User $user) {
    return view('user-profile-edit', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('user-profile-edit');

Route::get('/scrap-data/{user:username}', function (User $user) {
    return view('scrap-data', ['user' => $user, 'title' => 'Welcome ' . $user->name . '!']);
})->middleware('auth')->name('scrap-data');



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

Route::get('/users/edit/{user:id}', function (User $user) {
    return view('user-edit', [
        'title' => 'Edit User',
        'user' => $user,
    ]);
})->middleware('auth')->name('user.edit');
