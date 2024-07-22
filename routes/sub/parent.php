<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'parent.auth.register')->name('parent.register');
    // Volt::route('forgot-password', 'parent.auth.forgot-password')->name('parent.password.request');
    // Volt::route('reset-password/{token}', 'parent.auth.reset-password')->name('password.reset');        
    Volt::route('login', 'parent.auth.login')->name('parent.login');
});

Route::view('dashboard', 'tutor.dashboard')->name('dashboard');