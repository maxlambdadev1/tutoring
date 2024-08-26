<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\Job;
<<<<<<< HEAD
use App\Trait\Automationable;
=======
>>>>>>> 267b843f1cae45284aafc9bbf4c401d6a9f2f514
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorWelcomeEmail extends Command
{
<<<<<<< HEAD
    use Mailable, Automationable;
=======
    use Mailable;
>>>>>>> 267b843f1cae45284aafc9bbf4c401d6a9f2f514
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
<<<<<<< HEAD
        $now = new \DateTime();
        $yesterday = $now->modify("-1 day")->format('d/m/Y');
        $tutors = Tutor::where('tutor_creat', $yesterday)->get();
        foreach ($tutors as $tutor) {
            $f2f_jobs = $this->getJobsForTutor($tutor->id, 1, 4);
            $online_jobs = $this->getJobsForTutor($tutor->id, 2, 4);    
            $jobs = array_merge($f2f_jobs, $online_jobs);
            $jobs = array_slice($jobs, 0, 4);
            
            $match_content = "";
            if (!empty($jobs)) {
                $match_content .= "<strong>We want to get you going with students as soon as possible, so here are a few student options that match your availabilities, location and subjects that I think you would be perfect for:</strong><ul>";
                foreach ($jobs as $job) {
                    $match_content .= "<li>Year " . $job->child->child_year . " student " . $job->parent->parent_suburb . " - <a href='https://" . env("TUTOR") . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id) . "' target='_blank'>view details here</a></li>";
                }
                $match_content .= "</ul><br /><br />";
            }
            $params = [
                'email' => $tutor->tutor_email,
                'tutorfirstname' => $tutor->first_name,
                'matchcontent' => $match_content,
            ];
            $this->sendEmail($params['email'], "tutor-welcome-call-check-in-email", $params);

            $this->addTutorHistory([
                "tutor_id" => $tutor->id,
                "comment" => "Sent 'Welcome email' 24 hours after registering in system."
            ]);
        }
=======
>>>>>>> 267b843f1cae45284aafc9bbf4c401d6a9f2f514
    }
}
