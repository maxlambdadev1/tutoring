<?php

namespace App\Console\Commands;

use App\Models\Option;
use App\Models\Job;
use App\Trait\Automationable;
use App\Trait\Functions;
use Illuminate\Console\Command;

class F2fLessonAutomation extends Command
{
    use Functions, Automationable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:f2f-lesson-automation according to the distance with tutor and parent.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'F2F lesson automation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $crons = $this->getOption("cron-time");
        $cron_arr = unserialize($crons) ?? ['f2f_cron' => 60];

        $f2f_space = $this->getOption('f2f-space') ?? 0;
        if ($this->isWorkingHours()) {
            if ($f2f_space >= $cron_arr['f2f_cron']) {
                Option::where('option_name', 'f2f-space')->update(['option_value' => 0]);

                $jobs = Job::where('job_status', 0)->where('session_type_id', 1)
                    ->where('hidden', 0)->where('automation', 1)->get();
                foreach ($jobs as $job) {
                    $this->findTutorForF2F($job->id);
                }
            } else Option::where('option_name', 'f2f-space')->increment('option_value');
        }
    }
}
