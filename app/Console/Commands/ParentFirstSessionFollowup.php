<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\Job;
use App\Trait\Mailable;
use App\Trait\Sessionable;
use Illuminate\Console\Command;

class ParentFirstSessionFollowup extends Command
{
    use Mailable, Sessionable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parent-first-session-followup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'parent-first-lesson-followup' email to parent after 1.5hour from session date.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');

        $sessions = Session::where('session_parent_reminder', 0)
            ->where('session_is_first', 1)
            ->whereNot('session_status', 6)
            ->whereNot('session_status', 5)
            ->whereNull('session_next_session_id')
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) >= 5400")
            ->get();

        foreach ($sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_parent_reminder' => 1]);
                $link = $this->createNextSessionLink($session->id);
                $sms_params = [
                    'name' => $parent->parent_name,
                    'phone' => $parent->parent_phone,
                ];
                $body = "Hi " . $parent->parent_first_name . ", we really hope the first session went well! If you’d like to continue with regular lessons you can schedule in your next lesson and submit payment details here (takes just 2 minutes and you only do it once): " . $link . ". If you’d prefer to chat, please check your email and we’ll find a time to talk :) - Team Alchemy";
                $this->sendSms($sms_params, $body);

                $params = [
                    'tutorfirstname' => $tutor->first_name,
                    'studentname' => $child->first_name,
                    'parentfirstname' => $parent->parent_first_name,
                    'email' => $parent->parent_email,
                    'link' => $link,
                ];
                $this->sendEmail($params['email'], "parent-first-session-followup-email", $params);
            }
        }
    }
}
