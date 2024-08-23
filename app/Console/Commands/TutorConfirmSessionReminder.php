<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\Job;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorConfirmSessionReminder extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-confirm-session-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'tutor-session-reminder-email' and sms to tutor for unconfirmed session after 24, 48, 72hours.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');
        //after 24 hours.
        $sessions = Session::where('session_status', 1)
            ->where('session_after_session_reminder_18h', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) >= 86400")
            ->get();

        foreach ($sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_after_session_reminder_18h' => 1]);

                $params = [
                    'email' => $tutor->tutor_email,
                    'studentname' => $child->first_name,
                    'date' => $session->session_date,
                    'time' => $session->session_time,
                    'tutorfirstname' => $tutor->first_name,
                ];
                $this->sendEmail($params['email'], "tutor-session-reminder-email", $params);
            }
        }

        //after 48 hours.
        $sessions = Session::where('session_status', 1)
            ->where('session_after_session_reminder_30h', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) >= 172800")
            ->get();

        foreach ($sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_after_session_reminder_30h' => 1]);

                $params = [
                    'email' => $tutor->tutor_email,
                    'studentname' => $child->first_name,
                    'date' => $session->session_date,
                    'time' => $session->session_time,
                    'tutorfirstname' => $tutor->first_name,
                ];
                $this->sendEmail($params['email'], "tutor-session-reminder-email", $params);
            }
        }

        //after 72 hours.
        $sessions = Session::where('session_status', 1)
            ->where('session_after_session_reminder_42h', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) >= 259200")
            ->get();

        foreach ($sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_after_session_reminder_42h' => 1]);

                $sms_params = [
                    'name' => $tutor->tutor_name,
                    'phone' => $tutor->tutor_phone,
                ];
                $body = "Hi " . $tutor->first_name . ", your session with " . $child->first_name . " on " . $session->session_date . " is yet to be confirmed. Please do this in Dashboard ASAP (so you can be paid!), or if there are any issues please reply and let us know so we can help.";
                $this->sendSms($sms_params, $body, null, 1);
            }
        }
    }
}
