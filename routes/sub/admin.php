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
        Route::get('/setting/general', \App\Livewire\Admin\Setting\General::class)->name('setting.general');
        Route::get('/setting/templates', \App\Livewire\Admin\Setting\TemplateSettings::class)->name('setting.templates');
        Route::get('/setting/lead-setting', \App\Livewire\Admin\Setting\LeadSetting::class)->name('setting.lead-setting');
        Route::get('/setting/referral-setting', \App\Livewire\Admin\Setting\ReferralSetting::class)->name('setting.referral-setting');
        Route::get('/setting/promo-page', \App\Livewire\Admin\Setting\PromoPage::class)->name('setting.promo-page');
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
        
        Route::group(['prefix' => 'thirdparty', 'as' => 'thirdparty.'], function() {
            Route::get('/create', \App\Livewire\Admin\Thirdparty\CreateOrg::class)->name('create');
            Route::get('/{org}/edit', \App\Livewire\Admin\Thirdparty\EditOrg::class)->name('edit');
            Route::get('/create-lead', \App\Livewire\Admin\Thirdparty\CreateThirdpartyLead::class)->name('create-lead');
            Route::get('/sessions', \App\Livewire\Admin\Thirdparty\Sessions::class)->name('sessions');
            Route::get('/cancellation-fee', \App\Livewire\Admin\Thirdparty\CancellationFee::class)->name('cancellation-fee');
            Route::get('/organisations', \App\Livewire\Admin\Thirdparty\ThirdpartyOrgList::class)->name('organisations');
        });
        
        Route::group(['prefix' => 'creativekids', 'as' => 'creativekids.'], function() {
            Route::get('/add-creative-leads', \App\Livewire\Admin\Creativekids\AddLeads::class)->name('add-creative-leads');
        });
        
        Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function() {
            Route::get('/all-sessions', \App\Livewire\Admin\Sessions\AllSessions::class)->name('all-sessions');
            Route::get('/unconfirmed-sessions', \App\Livewire\Admin\Sessions\UnconfirmedSessions::class)->name('unconfirmed-sessions');
            Route::get('/scheduled-sessions', \App\Livewire\Admin\Sessions\ScheduledSessions::class)->name('scheduled-sessions');
            Route::get('/no-scheduled-sessions', \App\Livewire\Admin\Sessions\NoScheduledSessions::class)->name('no-scheduled-sessions');
            Route::get('/no-session-2-weeks', \App\Livewire\Admin\Sessions\NoSession2Week::class)->name('no-session-2-weeks');
            Route::get('/first-sessions', \App\Livewire\Admin\Sessions\FirstSessions::class)->name('first-sessions');
            Route::get('/daily-first-sessions', \App\Livewire\Admin\Sessions\DailyFirstSessions::class)->name('daily-first-sessions');
            Route::get('/not-continuing-sessions', \App\Livewire\Admin\Sessions\NotContinuingSessions::class)->name('not-continuing-sessions');
            Route::get('/progress-report', \App\Livewire\Admin\Sessions\ProgressReport::class)->name('progress-report');
            Route::get('/add-session', \App\Livewire\Admin\Sessions\AddSession::class)->name('add-session');
            Route::get('/cancellation-fee', \App\Livewire\Admin\Sessions\CancellationFee::class)->name('cancellation-fee');
            Route::get('/rescheduled-sessions', \App\Livewire\Admin\Sessions\RescheduledSessions::class)->name('rescheduled-sessions');
        });
        
        Route::group(['prefix' => 'tutors', 'as' => 'tutors.'], function() {
            Route::get('/current-tutors', \App\Livewire\Admin\Tutors\CurrentTutors::class)->name('current-tutors');
            Route::get('/new-tutors', \App\Livewire\Admin\Tutors\NewTutors::class)->name('new-tutors');
            Route::get('/past-tutors', \App\Livewire\Admin\Tutors\PastTutors::class)->name('past-tutors');
            Route::get('/tutor-application', \App\Livewire\Admin\Tutors\TutorApplication::class)->name('tutor-application');
            Route::get('/tutor-application-stats', \App\Livewire\Admin\Tutors\TutorApplicationStats::class)->name('tutor-application-stats');
            Route::get('/offers-volume', \App\Livewire\Admin\Tutors\OffersVolume::class)->name('offers-volume');
            Route::get('/tutor-check-in', \App\Livewire\Admin\Tutors\TutorCheckIn::class)->name('tutor-check-in');
            Route::get('/tutor-first-session', \App\Livewire\Admin\Tutors\TutorFirstSession::class)->name('tutor-first-session');
            Route::get('/set-online-room', \App\Livewire\Admin\Tutors\SetOnlineRoom::class)->name('set-online-room');
            Route::get('/have-references', \App\Livewire\Admin\Tutors\HaveReferencesTutors::class)->name('have-references');
            Route::get('/recruiter', \App\Livewire\Admin\Tutors\Recruiter::class)->name('recruiter');
        });
        
        Route::group(['prefix' => 'wwcc', 'as' => 'wwcc.'], function() {
            Route::get('/verify-wwcc', \App\Livewire\Admin\Wwcc\VerifyWwcc::class)->name('verify-wwcc');
            Route::get('/chasing-wwcc', \App\Livewire\Admin\Wwcc\ChasingWwcc::class)->name('chasing-wwcc');
            Route::get('/audit-wwcc', \App\Livewire\Admin\Wwcc\AuditWwcc::class)->name('audit-wwcc');
        });
        
        Route::group(['prefix' => 'students', 'as' => 'students.'], function() {
            Route::get('/current-students', \App\Livewire\Admin\Students\CurrentStudents::class)->name('current-students');
            Route::get('/past-students', \App\Livewire\Admin\Students\PastStudents::class)->name('past-students');
            Route::get('/add-students', \App\Livewire\Admin\Students\AddStudents::class)->name('add-students');
        });

        Route::group(['prefix' => 'parents', 'as' => 'parents.'], function() {
            Route::get('/current-parents', \App\Livewire\Admin\Parents\ParentList::class)->name('current-parents');
            Route::get('/parents-payment-details', \App\Livewire\Admin\Parents\ParentsPaymentList::class)->name('parents-payment-details');
            Route::get('/manual-payers', \App\Livewire\Admin\Parents\ManualPayers::class)->name('manual-payers');
            Route::get('/parent-check-in', \App\Livewire\Admin\Parents\ParentCheckIn::class)->name('parent-check-in');
        });
        
        Route::group(['prefix' => 'payments', 'as' => 'payments.'], function() {
            Route::get('/price-audit', \App\Livewire\Admin\Payments\PriceAudit::class)->name('price-audit');
            Route::get('/edit-prices', \App\Livewire\Admin\Payments\EditPrices::class)->name('edit-prices');
            Route::get('/failed-payments', \App\Livewire\Admin\Payments\FailedPayments::class)->name('failed-payments');
            Route::get('/manual-payments', \App\Livewire\Admin\Payments\ManualPayments::class)->name('manual-payments');
            Route::get('/margins', \App\Livewire\Admin\Payments\Margins::class)->name('margins');
        });
        
        Route::group(['prefix' => 'end-of-holiday', 'as' => 'end-of-holiday.'], function() {
            Route::get('/new-year-tutor', \App\Livewire\Admin\EndOfHoliday\NewYearTutor::class)->name('new-year-tutor');
            Route::get('/new-year-student', \App\Livewire\Admin\EndOfHoliday\NewYearStudent::class)->name('new-year-student');
            Route::get('/new-year-student-not-scheduled', \App\Livewire\Admin\EndOfHoliday\NewYearStudentNotScheduled::class)->name('new-year-student-not-scheduled');
            Route::get('/replacement', \App\Livewire\Admin\EndOfHoliday\Replacement::class)->name('replacement');
        });
        
        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function() {
            Route::get('/daily-report', \App\Livewire\Admin\Reports\DailyReport::class)->name('daily-report');
            Route::get('/conversion-report', \App\Livewire\Admin\Reports\ConversionReport::class)->name('conversion-report');
            Route::get('/all-sessions', \App\Livewire\Admin\Reports\AllSessions::class)->name('all-sessions');
            Route::get('/team-goals', \App\Livewire\Admin\Reports\TeamGoals::class)->name('team-goals');
            Route::get('/all-sessions-google', \App\Livewire\Admin\Reports\AllSessionsGoogle::class)->name('all-sessions-google');
            Route::get('/monthly-report', \App\Livewire\Admin\Reports\MonthlyReport::class)->name('monthly-report');
        });
        
        Route::group(['prefix' => 'community', 'as' => 'community.'], function() {
            Route::get('/feedback', \App\Livewire\Admin\Community\Feedback::class)->name('feedback');
        });
    });
});
