<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\Job;
use App\Models\BlockFromJob;
use App\Models\Session;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class BlockFromJobs extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:block-from-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Block tutor from accepting jobs that have 3 not-continuing-sessions or have 8 jobs last 3 months ago.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tutors = Tutor::where('tutor_status', 1)->where('accept_job_status', 1)->get();
        foreach ($tutors as $tutor) {
            $not_continuing_sessions = Session::where('tutor_id', $tutor->id)->where('session_status', 5)->get();
            if ($not_continuing_sessions >= 3) {
                $block_from_job = BlockFromJob::where('tutor_id', $tutor->id)->where('not_continue_number', $not_continuing_sessions)->where('type', 1)->first();
                if (empty($block_from_job)) {
                    BlockFromJob::create([
                        'tutor_id' => $tutor->id,
                        'not_continue_number' => $not_continuing_sessions,
                    ]);
                    $tutor->update(['accept_job_status' => 0]);
                    $this->addTutorHistory([
                        'tutor_id' => $tutor->id,
                        'comment' => "Tutor has been blocked from accepting jobs due to too many not-continuing first sessions.(Total number: " . $not_continuing_sessions . ")"
                    ]);
                    continue;
                }
            }

            $jobs = Job::where('job_status', 1)->where('accepted_by', $tutor->id)
                ->whereRaw("STR_TO_DATE(accepted_on, '%d/%m/%Y') between date_sub(now(),INTERVAL 90 DAY) AND now()")
                ->count();
            if ($jobs >= 8) {
                $block_from_job = BlockFromJob::where('tutor_id', $tutor->id)->where('not_continue_number', $jobs)->where('type', 2)->first();
                if (empty($block_from_job)) {
                    BlockFromJob::create([
                        'tutor_id' => $tutor->id,
                        'not_continue_number' => $jobs,
                    ]);
                    $tutor->update(['accept_job_status' => 0]);
                    $this->addTutorHistory([
                        'tutor_id' => $tutor->id,
                        'comment' => "Tutor has accepted 8 jobs in 3 months - block placed on account.(Total number: " . $jobs . ")"
                    ]);
                    continue;
                }
            }
        }
    }
}
