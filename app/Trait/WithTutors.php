<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutorWwcc;
use App\Models\TutorWwccValidate;
use App\Models\Child;
use App\Models\TutorApplication;
use App\Models\TutorApplicationQueue;
use App\Trait\Functions;

trait WithTutors {
    
    use Functions, Mailable;

    
	public const APPLICATION_STATUS = array(
        '1' => 'Applied as tutor',
        '2' => 'Scheduling interview',
        '3' => 'Interview scheduled',
        '4' => 'Awaiting to register',
        '5' => 'Registered',
        '6' => 'Rejected',
        '7' => 'Auto-reject',
        '8' => 'No atar',
        '9' => 'Closed'
	);

    /**
     * @param $params = ['email' => , 'appid' => , 'tutorfirstname' => , 'tutoremail' => ]
     */
    public function tutorApplicationQueue($params) {
        TutorApplicationQueue::updateOrCreate([
            'tutor_email' => $params['tutoremail']
        ], [
            'app_id' => $params['appid'],
            'tutor_first_name' => $params['tutorfirstname'],
            'email' => $params['email'],
            'date_lastupdated' => time()
        ]);
    }

    /**
     * @param $post = ['application_id' =>, 'status' => ]
     */
    public function acceptTutorApplication($post)
    { 
        try {
            $app_id = $post['application_id'];
            $app_status = $post['status']; 

            $app = TutorApplication::find($app_id);
            $application_status = $app->application_status; 
            $application_status->update([
                'application_status' => $app_status,
                'date_follow_up' => (new \DateTime('now'))->format('d/m/Y H:i'),
                'followup_counter' => 0
            ]);

            $params = [
                'tutorfirstname' => $app->tutor_first_name,
                'tutoremail' => $app->tutor_email,
                'appid' => $app->id
            ];

            if ($app_status == 2) { //'Scheduling interview'
                $this->sendEmail($app->tutor_email, 'tutor-application-interview', $params);

                $smsParams = [
                    'name' => $app->tutor_first_name . ' ' . $app->tutor_last_name,
                    'phone' => $app->tutor_phone,
                ];
                $this->sendSms($smsParams, '', $params);
            } else if ($app_status == 4) { //'Awaiting to register'
                $params['email'] = 'tutor-application-register';
                $this->tutorApplicationQueue($params);
            } else if ($app_status == 6) { //'Rejected'
                $params['email'] = 'tutor-application-reject';
                $this->tutorApplicationQueue($params);
            } else if ($app_status == 7) { //'Auto-rejected'
                $params['email'] = 'tutor-application-hold';
                $this->tutorApplicationQueue($params);
            }

            $status =  $this::APPLICATION_STATUS[$app_status] ?? '';
            $this->addTutorApplicationHistory([
                'application_id' => $app->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Changed status to " .$status
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * block or unblock tutor from jobs
     * @param $tutor_id
     */
    public function blockOrUnblockFromJobs($tutor_id)
    {
        try {
            $tutor = Tutor::find($tutor_id);
            $updated = $tutor->accept_job_status == 1 ? 0 : 1;
            $tutor->update([
                'accept_job_status' => $updated
            ]);

            $comment = $updated == 0 ? 'Blocked tutor from accepting jobs.' : 'Unblocked tutor from accepting jobs.';

            $this->addTutorHistory([
                'tutor_id' => $tutor_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * verify wwcc tutor
     * @param $tutor_id
     */
    public function verifyWWCC($tutor_id)
    {
        try {
            $tutor = Tutor::find($tutor_id);

            $today = new \DateTime();
            TutorWwcc::updateOrCreate([
                'tutor_id' => $tutor_id
            ], [
                'verified_on' => $today->format('d/m/Y H:i'),
                'verified_by' => auth()->user()->admin->admin_name
            ]);

            TutorWwccValidate::where('tutor_id', $tutor_id)->delete();

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}