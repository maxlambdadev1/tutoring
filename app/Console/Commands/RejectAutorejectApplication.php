<?php

namespace App\Console\Commands;

use App\Models\TutorApplication;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class RejectAutorejectApplication extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reject-autoreject-application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Reject after 72 hours because this applicant is not matched with our criteria";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        //for reference_reminder_48h == 0
        $applications = TutorApplication::query()
        ->join('alchemy_tutor_application_status', function ($status) {
            $status->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
        })
        ->where('application_status', 7)
        ->whereRaw("TIMESTAMPDIFF(HOUR, alchemy_tutor_application.created_at, NOW()) >=72")
        ->select('alchemy_tutor_application.*')
        ->get();

        foreach ($applications as $application) {
            $checking_flag = false;
            if ($application->tutor_graduation_year < (date('Y') - 4)) $checking_flag = true;
            if ($application->tutor_highschool_aus != "Yes") $checking_flag = true;
            else {
                if (empty($application->tutor_atar) || $application->atar == '70-79' || $application->atar == '80-89' || $application->atar == '90-95' || $application->atar == '95+') $checking_flag = true;
            }
            if (!!$checking_flag) {
                $status = $application->application_status;
                $status->update([
                    'application_status' => 6,
                    'date_last_update' => (new \DateTime())->format('d/m/Y H:i')
                ]);
                $params = [
                    'email' => $application->tutor_email,
                    'tutorfirstname' => $application->tutor_first_name,
                ];
                $this->sendEmail($params['email'], "tutor-application-reject", $params);
                $this->addTutorApplicationHistory([
                    'application_id' => $application->id,
                    'comment' => "Auto rejected after 72 hours because this applicant is not matched with our criteria"
                ]);
            }
        }
    }
}
