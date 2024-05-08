<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return redirect('login');
})->name('welcome');

// Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// require __DIR__.'/auth.php';


// ----- ADMIN -----
Route::domain(env('ADMIN'))->group(base_path('routes/sub/admin.php'));

// ----- TUTOR -----
Route::domain(env('TUTOR'))->group(base_path('routes/sub/tutor.php'));

// ----- PARENT ----
Route::domain(env('PARENT'))->group(base_path('routes/sub/parent.php'));

// ----- RECRUITER ----
Route::domain(env('RECRUITER'))->group(base_path('routes/sub/recruiter.php'));