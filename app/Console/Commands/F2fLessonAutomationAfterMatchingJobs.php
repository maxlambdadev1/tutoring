<?php

namespace App\Console\Commands;

use App\Models\JobMatch;
use App\Models\Tutor;
use App\Models\Job;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use Illuminate\Console\Command;

class F2fLessonAutomationAfterMatchingJobs extends Command
{
    use Functions, WithLeads, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:f2f-lesson-automation-after-matching-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "F2f automation based on jobs_match table after automationing jobs according to the distance.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = new \DateTime();
        $jobs = Job::where('job_status', 0)->where('hidden', 0)->where('automation', 1)->where('session_type_id', 1)
            ->whereNot('job_type')->get();
        foreach ($jobs as $job) {
            $job_match = $job->job_match;
            if (!empty($job_match)) {
                $parent = $job->parent;
                $child = $job->child;
                if (empty($job_match->last_updated)) continue;
                $last_updated = \DateTime::createFromFormat('d/m/Y H:i', $job_match->last_updated);
                $timediff = $datetime->getTimestamp() - $last_updated->getTimestamp();
                $ignored_tutors = $this->getIgnoredTutorsForJob($job->id);
                switch ($job_match->automation_step) {
                    case 0:
                        if ($job_match->reminder1 == 0) {
                            $tutor_ids = $job_match->tutor_ids_array;
                            if (empty($tutor_ids)) {
                                foreach ($tutor_ids as $tutor_id) {
                                    $tutor = Tutor::find($tutor_id);
                                    if (!empty($ignored_tutors) && in_array($tutor->id, $ignored_tutors)) continue;
                                    $sms_params = [
                                        'name' => $tutor->tutor_name,
                                        'phone' => $tutor->tutor_phone,
                                    ];
                                    $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                                    $body = "Hey " . $tutor->first_name . ", just a reminder that this student in " . $job->location . " is still available: " . $this->setRedirect($link) . " .";
                                    $this->sendSms($sms_params, $body, null, 1);
                                    $this->addJobAutomationHistory([
                                        'job_id' => $job->id,
                                        'comment' => "Reminder1 SMS sent to " . $tutor->tutor_name,
                                    ]);
                                }
                            } else {
                                if ($timediff < 6 * 86400 && $timediff >= 1 * 86400) {
                                    if (($timediff - 1 * 86400) > 86400 * $job_match->update_avail_status) {
                                        
                                    }
                                }
                            }
                        }
                        break;
                }

            }
        }
    }
}
