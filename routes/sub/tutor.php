<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Tutor\TutorController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/register', \App\Livewire\Tutor\Auth\Register::class)->name('register');
Route::get('/check-user', [TutorController::class, 'checkUser'])->name('check-user');

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
    
    Route::group(['prefix' => 'jobs', 'as' => 'jobs.'], function() {
        Route::get('/all-jobs', \App\Livewire\Tutor\Jobs\AllJobs::class)->name('all-jobs');
        Route::get('/jobs-map', \App\Livewire\Tutor\Jobs\JobsMap::class)->name('jobs-map');
        Route::get('/{job_id}', \App\Livewire\Tutor\Jobs\Detail::class)->name('detail');
    });

    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function() {
        Route::get('/previous-sessions', \App\Livewire\Tutor\Sessions\PreviousSession::class)->name('previous-sessions');
        Route::get('/unconfirmed-sessions', \App\Livewire\Tutor\Sessions\UnconfirmedSession::class)->name('unconfirmed-sessions');
        Route::get('/scheduled-sessions', \App\Livewire\Tutor\Sessions\ScheduledSession::class)->name('scheduled-sessions');
        Route::get('/add-session', \App\Livewire\Tutor\Sessions\AddSession::class)->name('add-session');
    });
    
    Route::get('/your-students', \App\Livewire\Tutor\YourStudents::class)->name('your-students');
    Route::get('/payments', \App\Livewire\Tutor\Payments::class)->name('payments');
    
    Route::group(['prefix' => 'your-detail', 'as' => 'your-detail.'], function() {
        Route::get('/update-detail', \App\Livewire\Tutor\YourDetail\UpdateDetail::class)->name('update-detail');
        Route::get('/update-subjects', \App\Livewire\Tutor\YourDetail\UpdateSubjects::class)->name('update-subjects');
        Route::get('/update-availabilities', \App\Livewire\Tutor\YourDetail\UpdateAvailabilities::class)->name('update-availabilities');
        Route::get('/update-payment', \App\Livewire\Tutor\YourDetail\UpdatePayment::class)->name('update-payment');
        Route::get('/update-wwcc', \App\Livewire\Tutor\YourDetail\UpdateWwcc::class)->name('update-wwcc');
        Route::get('/profile', \App\Livewire\Tutor\YourDetail\Profile::class)->name('profile');
    });
    
    Route::get('/refer-friends', \App\Livewire\Tutor\ReferFriends::class)->name('refer-friends');
    
});

Route::group(['as' => 'pages'], function() {
    Route::get('/apply', \App\Livewire\Tutor\Pages\ApplyTutorApplication::class)->name('apply');
    Route::get('/application-success', \App\Livewire\Tutor\Pages\ApplicationSuccess::class)->name('application-success');
});
