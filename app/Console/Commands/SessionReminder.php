<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\Job;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class SessionReminder extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:session-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send reminder email to tutor for tomorrow first lesson.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');

        $scheduled_sessions = Session::where('session_status', 3)
            ->where('session_reminder', 0)
            ->where('session_is_first', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('". $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) <= 86400")
            ->get();

        foreach ($scheduled_sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_reminder' => 1]);

                $job = Job::where('parent_id', $parent->id)->where('child_id', $child->id)->where('accepted_by', $tutor->id)->where('session_id', $session->id)->first();
                if (!empty($job)) {
                    if ($job->job_type == 'creative') {
                        $sms_params = [
                            'name' => $tutor->tutor_name,
                            'phone' => $tutor->tutor_phone
                        ];
                        $body = "Reminder: Creative Writing workshop with ".$child->child_name." is scheduled for tomorrow at ".$session->session_time.". Be sure to do your training in Tutorhub beforehand! You’ve got this! Team Alchemy";
                        $this->sendSms($sms_params, $body, null, true);
                        
                        $sms_params = [
                            'name' => $parent->parent_name,
                            'phone' => $parent->parent_phone
                        ];
                        $body = "Reminder: The Alchemy Creative Writing workshop for ".$child->child_name." is happening tomorrow at ".$session->session_time.". If you have any questions please don’t hesitate to get in touch!";
                        $this->sendSms($sms_params, $body, null, true);
                    }
                } else {
                    $sms_params = [
                        'name' => $tutor->tutor_name,
                        'phone' => $tutor->tutor_phone
                    ];
                    $body = "Get pumped! Your first session with ".$child->child_name." is happening tomorrow at ".$session->session_time.". If you need any help preparing just reach out to us via live chat in your dashboard and we will be more than happy to help!";
                    $this->sendSms($sms_params, $body, null, true);

                    $params = [
                        'phone' => $tutor->tutor_phone,
                        'email' => $tutor->tutor_email,
                        'tutorfirstname' => $tutor->first_name,
                        'studentname' => $child->child_name,
                        'date' => $session->session_date,
                        'time' => $session->session_time,
                        'address' => $parent->parent_address . ", " . $parent->parent_suburb . ", " . $parent->parent_postcode,
                        'subject' => $session->session_subject,
                        'grade' => $child->child_year ?? '',
                        'notes' => $job->job_notes ?? '',
                        'parentname' => $parent->parent_name,
                        'parentphone' => $parent->parent_phone,
                        'onlineurl' => $tutor->online_url,
                    ];
                    if ($session->type_id == 2) $this->sendEmail($params['email'], "tutor-first-online-session-reminder-email", $params);
                    else  $this->sendEmail($params['email'], "tutor-first-session-reminder-email", $params);
                }
            }
        }
    }
}
