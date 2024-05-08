<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Volt::route('register', 'admin.auth.register')->name('admin.register');           
    // Volt::route('forgot-password', 'admin.auth.forgot-password')->name('admin.password.request');
    // Volt::route('reset-password/{token}', 'admin.auth.reset-password')->name('admin.password.reset');        
    Volt::route('login', 'admin.auth.login')->name('admin.login');
});

Route::group(['middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    // Volt::route('verify-email', 'admin.auth.verify-email')->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
    // Volt::route('confirm-password', 'admin.auth.confirm-password')->name('password.confirm');

    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::middleware('can:action-owner')->group(function() {

    });

    Route::middleware('can:action-manager')->group(function() {
        
    });
});
