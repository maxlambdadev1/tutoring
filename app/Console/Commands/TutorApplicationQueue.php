<?php

namespace App\Console\Commands;

use App\Models\TutorApplicationQueue as Queue;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorApplicationQueue extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-application-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Action for tutor applicaton queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $queues = Queue::where('date_lastupdated', '<=', time() - 18000)->get();
        foreach ($queues as $queue) {
            $application = $queue->tutor_application;
            if (!empty($application)) {
                $application_status = $application->application_status;
                if (!empty($application_status)) {
                    if ($application_status->application_status == 4) {
                        $body = "Hi " . $application->tutor_first_name . ", , we loved meeting you and think you would make an amazing part of our team! Let’s get you set up in our system and started with your first student! Please check your email for next steps and more info - Team Alchemy";
                        $sms_params = [
                            'name' => $application->tutor_name,
                            'phone' => $application->tutor_phone,
                        ];
                        $this->sendSms($sms_params, $body);

                        $secret = sha1($application->id . env('SHARED_SECRET'));
                        $params = [
                            'email' => $application->tutor_email,
                            'tutorfirstname' => $application->tutor_first_name,
                            'registerurl' => "https://" . env('TUTOR') . "/register?url=" . base64_encode("secret=" . $secret . "&application_id=" . $application->id),
                        ];
                        $this->sendEmail($params['email'], "tutor-application-register", $params);

                        $this->addTutorApplicationHistory([
                            'application_id' => $application->id,
                            'comment' => "Sent " . $application->tutor_email . " delayed unique-register email",
                        ]);
                    } else if ($application_status->application_status == 6) {
                        $params = [
                            'email' => $application->tutor_email,
                            'tutorfirstname' => $application->tutor_first_name,
                        ];
                        $this->sendEmail($params['email'], "tutor-application-reject", $params);

                        $this->addTutorApplicationHistory([
                            'application_id' => $application->id,
                            'comment' => "Sent " . $application->tutor_email . " delayed  rejection email.",
                        ]);
                    }
                }
            }
            $queue->delete();
        }
        
        //glassdoor review
        $queues = Queue::where('date_lastupdated', '<=', time() - 1800)->where('glassdoor_review', 0)->get();
        foreach ($queues as $queue) {
            $application = $queue->tutor_application;
            if (!empty($application)) {
                $application_status = $application->application_status;
                if (!empty($application_status) && $application_status->application_status == 4) {
                    $this->addTutorApplicationHistory([
                        'application_id' => $application->id,
                        'comment' => "Sent glassdoor email to " . $application->tutor_email,
                    ]);
                    $queue->update(['glassdoor_review' => 1]);
                    $params = [
                        'email' => $application->tutor_email,
                        'tutorfirstname' => $application->tutor_first_name,
                    ];
                    $this->sendEmail($params['email'], "tutor-application-glassdoor-review", $params);
                }
            }
        }
    }
}
