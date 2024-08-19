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

    Route::get('/dashboard',  \App\Livewire\Tutor\Dashboard::class)->name('dashboard');
    
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
    Route::get('/book-now', \App\Livewire\Tutor\Pages\BookLead::class)->name('book-now');
    Route::get('/student-opportunity', \App\Livewire\Tutor\Pages\StudentOpportunity::class)->name('student-opportunity');
    Route::get('/taken-student', \App\Livewire\Tutor\Pages\SnatchedStudent::class)->name('taken-student');
    Route::get('/accept-waiting-list', \App\Livewire\Tutor\Pages\AcceptWaitingList::class)->name('accept-waiting-list');
    Route::get('/reject-waiting-list', \App\Livewire\Tutor\Pages\RejectWaitingList::class)->name('reject-waiting-list');
    Route::get('/update-availabilities', \App\Livewire\Tutor\Pages\UpdateJobAvailabilities::class)->name('update-availabilities');
    Route::get('/progress-report', \App\Livewire\Tutor\Pages\ProgressReport::class)->name('progress-report');
    Route::get('/tutor-review', \App\Livewire\Tutor\Pages\TutorReview::class)->name('tutor-review');
    Route::get('/tutorvote', \App\Livewire\Tutor\Pages\TutorVote::class)->name('tutorvote');
    Route::get('/reference', \App\Livewire\Tutor\Pages\Reference::class)->name('reference');
    Route::get('/thankyou-parent', \App\Livewire\Tutor\Pages\ThankyouParent::class)->name('thankyou-parent');
    Route::get('/feedback', \App\Livewire\Tutor\Pages\Feedback::class)->name('feedback');
    Route::get('/replacement-tutor', \App\Livewire\Tutor\Pages\ReplacementTutor::class)->name('replacement-tutor');
    Route::get('/confirm-session', \App\Livewire\Tutor\Pages\ConfirmSession::class)->name('confirm-session');
    Route::get('/paymentcc', \App\Livewire\Tutor\Pages\PaymentCc::class)->name('paymentcc');
    Route::get('/tutor/{tutor_id}', \App\Livewire\Tutor\Pages\TutorProfile::class)->name('tutor-profile');
});
