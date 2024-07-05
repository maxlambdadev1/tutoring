<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Volt::route('register', 'tutor.auth.register')->name('tutor.register');
    Volt::route('forgot-password', 'tutor.auth.forgot-password')->name('tutor.password.request');
    Volt::route('reset-password/{token}', 'tutor.auth.reset-password')->name('password.reset');        
    Volt::route('login', 'tutor.auth.login')->name('tutor.login');
});

Route::group(['middleware' => ['auth', 'role:tutor', 'verified'], 'as' => 'tutor.'], function () {
    // Volt::route('verify-email', 'tutor.auth.verify-email')->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
    // Volt::route('confirm-password', 'tutor.auth.confirm-password')->name('password.confirm');

    Route::view('dashboard', 'tutor.dashboard')->name('dashboard');

    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function() {
        Route::get('/previous-sessions', \App\Livewire\Tutor\Sessions\PreviousSession::class)->name('previous-sessions');
        Route::get('/unconfirmed-sessions', \App\Livewire\Tutor\Sessions\UnconfirmedSession::class)->name('unconfirmed-sessions');
        Route::get('/scheduled-sessions', \App\Livewire\Tutor\Sessions\ScheduledSession::class)->name('scheduled-sessions');
    });
});
