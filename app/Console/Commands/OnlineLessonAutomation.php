<?php

namespace App\Console\Commands;

use App\Models\Option;
use App\Models\Job;
use App\Trait\Automationable;
use App\Trait\Functions;
use Illuminate\Console\Command;

class OnlineLessonAutomation extends Command
{
    use Functions, Automationable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:online-lesson-automation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Online lesson automation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $crons = $this->getOption("cron-time");
        $cron_arr = unserialize($crons) ?? ['online_cron' => 15];

        $online_space = $this->getOption('online-space') ?? 0; 
        if ($this->isWorkingHours()) {
            if ($online_space >= $cron_arr['online_cron']) {
                Option::where('option_name', 'online-space')->update(['option_value' => 0]);

                $jobs = Job::where('job_status', 0)->where('session_type_id', 2)
                    ->where('hidden', 0)->where('automation', 1)->get();
                foreach ($jobs as $job) {
                    $this->findTutorForOnline($job->id);
                }
            } else Option::where('option_name', 'online-space')->increment('option_value');
        }
    }
}
