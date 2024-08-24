<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutorApplicationStatus;
use App\Models\TutorWwcc;
use App\Models\TutorWwccValidate;
use App\Models\Child;
use App\Models\TutorApplication;
use App\Models\TutorApplicationQueue;
use App\Models\TutorSpecialReferralEmail;
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

    public const   FOLLOWUP_CATEGORY_FOR_STUDENT = [
        '1' => 'Deleted lead - parent choice',
        '2' => 'Deleted lead - unable to help',
        '3' => 'Cancelled first session (no lessons happened)',
        '4' => 'Cancelled lessons (had regular lessons)'
    ];

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

                $sms_params = [
                    'name' => $app->tutor_first_name . ' ' . $app->tutor_last_name,
                    'phone' => $app->tutor_phone,
                ];
                $this->sendSms($sms_params, '', $params);
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
                'author' => auth()->user()->admin->admin_name ?? 'Syetem',
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

    /**
     * if the tutor created in 30 dyas and exist the tutor_special_referral_email, result is true, else false.
     * @param mixed $tutor_id
     * @throws \Exception
     * @return bool
     */
    public function getReferralSpecial($tutor_id) {
        try {
            $tutor = Tutor::find($tutor_id);
            $today = new \DateTime('now');
            $created_at = \DateTime::createFromFormat('d/m/Y', trim($tutor->tutor_creat));
            if ($today >= $created_at) {
                $interval = $today->diff($created_at);
                if ($interval->days <= 30) {
                    $tutor_special = TutorSpecialReferralEmail::where('tutor_id', $tutor_id)->first();
                    if (!empty($tutor_special)) return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * update tutor application followup counter.
     * @param int $application_id
     * @return void
     */
    public function updateTutorApplicationFollowup($application_id, $counter, $type) {
        TutorApplicationStatus::where('application_id', $application_id)
            ->update([
                'date_follow_up' => gmdate('d/m/Y H:i'),
                'followup_counter' => $counter ?? 0,
            ]);

        $this->addTutorApplicationHistory([
            'application_id' => $application_id,
            'comment' => "Sent " . $type . " follow up.",
        ]);
    }

    /**
     * Close the tutor application due to lack of response from tutor.
     * @param int $application_id
     * @return void
     */
    public function closeTutorApplication($application_id) {
        TutorApplicationStatus::where('application_id', $application_id)->update(['application_status' => 9]);
        $this->addTutorApplicationHistory([
            'application_id' => $application_id,
            'comment' => "Closed application due to the lack of response from tutor."
        ]);
    }
}