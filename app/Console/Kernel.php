<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:make-charge')->everyThirtyMinutes();
        $schedule->command('app:session-reminder')->everyFifteenMinutes();
        $schedule->command('app:change-session-status')->everyThirtyMinutes();
        $schedule->command('app:tutor-stripe-account-error')->cron("0 11 */2 * *");
        $schedule->command('app:parent-first-session-followup')->everyThirtyMinutes();
        $schedule->command('app:parent-first-session-reminder')->everyThirtyMinutes();
        $schedule->command('app:tutor-confirm-session-reminder')->everyThirtyMinutes();
        $schedule->command('app:payment-info-followup')->cron("0 17 * * *");
        $schedule->command('app:glassdoor-review')->cron("0 17 * * *");
        $schedule->command('app:online-lesson-automation')->everyMinute();
        $schedule->command('app:f2f-lesson-automation')->everyMinute();
        $schedule->command('app:tutor-application-queue')->cron("*/30 7-21 * * *");
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
