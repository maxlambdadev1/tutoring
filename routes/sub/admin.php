<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Volt::route('register', 'admin.auth.register')->name('admin.register');           
    // Volt::route('forgot-password', 'admin.auth.forgot-password')->name('admin.password.request');
    // Volt::route('reset-password/{token}', 'admin.auth.reset-password')->name('admin.password.reset');        
    Volt::route('login', 'admin.auth.login')->name('login');
});

Route::group(['middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    // Volt::route('verify-email', 'admin.auth.verify-email')->name('verification.notice');
    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
    // Volt::route('confirm-password', 'admin.auth.confirm-password')->name('password.confirm');

    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::middleware('can:action-owner')->group(function() {     
        Route::get('/users', \App\Livewire\Admin\User\Index::class)->name('users');
        Route::get('/users/create', \App\Livewire\Admin\User\Create::class)->name('users.create');
        Route::get('/users/{admin}/edit', \App\Livewire\Admin\User\Edit::class)->name('users.edit');
        Route::get('/setting/states-grades', \App\Livewire\Admin\Setting\StatesGrades::class)->name('setting.states-grades');
        Route::get('/setting/subjects', \App\Livewire\Admin\Setting\Subjects::class)->name('setting.subjects');
        Route::get('/setting/session-types', \App\Livewire\Admin\Setting\SessionTypes::class)->name('setting.session-types');
        Route::get('/setting/availabilities', \App\Livewire\Admin\Setting\Availabilities::class)->name('setting.availabilities');
    });

    Route::middleware('can:action-manager')->group(function() {
        Route::group(['prefix' => 'leads', 'as' => 'leads.'], function() {
            Route::get('/create', \App\Livewire\Admin\Leads\Create::class)->name('create');
            Route::get('/all-leads', \App\Livewire\Admin\Leads\AllLeads::class)->name('all-leads');
            Route::get('/leads-screening', \App\Livewire\Admin\Leads\LeadsScreening::class)->name('leads-screening');
            Route::get('/new-leads', \App\Livewire\Admin\Leads\NewLeads::class)->name('new-leads');
            Route::get('/active-leads', \App\Livewire\Admin\Leads\ActiveLeads::class)->name('active-leads');
            Route::get('/focus-leads', \App\Livewire\Admin\Leads\FocusLeads::class)->name('focus-leads');
            Route::get('/deleted-leads', \App\Livewire\Admin\Leads\DeletedLeads::class)->name('deleted-leads');
            Route::get('/waiting-list', \App\Livewire\Admin\Leads\WaitingList::class)->name('waiting-list');
            Route::get('/replacement-tutor', \App\Livewire\Admin\Leads\ReplacementTutor::class)->name('replacement-tutor');
            Route::get('/special-requirement-approval', \App\Livewire\Admin\Leads\SpecialRequirementApproval::class)->name('special-requirement-approval');
        });        
    });
});
