<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'recruiter.auth.register')->name('recruiter.register');
    Volt::route('forgot-password', 'recruiter.auth.forgot-password')->name('recruiter.password.request');
    Volt::route('reset-password/{token}', 'recruiter.auth.reset-password')->name('password.reset');        
    Volt::route('login', 'recruiter.auth.login')->name('recruiter.login');
});

Route::group(['middleware' => ['auth', 'role:recruiter', 'verified'], 'as' => 'recruiter.'], function () {
    // Volt::route('verify-email', 'recruiter.auth.verify-email')->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
    // Volt::route('confirm-password', 'recruiter.auth.confirm-password')->name('password.confirm');

    Route::get('/dashboard', \App\Livewire\Recruiter\ReferFriends::class)->name('dashboard');
    Route::get('/referral-pack', \App\Livewire\Recruiter\ReferralPack::class)->name('referral-pack');

});