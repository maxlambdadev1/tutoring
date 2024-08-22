<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\Job;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class ParentFirstSessionReminder extends Command
{
    use Mailable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parent-first-session-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send first session notifying email to parent before two days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');

        $scheduled_sessions = Session::where('session_status', 3)
            ->where('session_before_session_reminder', 0)
            ->where('session_is_first', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) <= 172800")
            ->get();

        foreach ($scheduled_sessions as $session) {
            $tutor = $session->tutor;
            $parent = $session->parent;
            $child = $session->child;
            if (!empty($tutor) && !empty($parent) && !empty($child)) {
                $session->update(['session_before_session_reminder' => 1]);

                $job = Job::where('parent_id', $parent->id)->where('child_id', $child->id)->where('accepted_by', $tutor->id)->where('session_id', $session->id)->first();
                if (!empty($job)) {
                    if ($job->job_type == 'creative') continue;
                    else {
                        $params = [
                            'email' => $parent->parent_email,
                            'studentname' => $child->first_name,
                            'date' => $session->session_date,
                            'time' => $session->session_time,
                            'parentfirstname' => $parent->parent_first_name,
                            'onlineurl' => $tutor->online_url,
                        ];
                        if ($session->type_id == 2) $this->sendEmail($params['email'], "parent-before-first-online-session-email", $params);
                        else  $this->sendEmail($params['email'], "parent-before-first-session-email", $params);
                    }
                }
            }
        }
    }
}
