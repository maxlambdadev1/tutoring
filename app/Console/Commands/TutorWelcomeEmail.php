<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\Job;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorWelcomeEmail extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-welcome-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome email to tutor after 1 day from creating with jobs list for the tutor.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
