<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\WaitingLeadOffer;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class WaitingListJobReminder extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:waiting-list-job-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Waiting list action';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = new \DateTime('now');
        $jobs = Job::where('job_status', 3)->get();
        foreach ($jobs as $job) {
            $parent = $job->parent;
            $child = $job->child;
            $waiting = WaitingLeadOffer::where('job_id', $job->id)->where('status', 0)->where('reminder', 0)
                ->whereRaw("TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24")->orderBy('id', 'DESC')->first();
            if (!empty($waiting)) {
                $secret = sha1($waiting->id . env('SHARED_SECRET'));
                $params = [
                    'email' => $parent->parent_email,
                    'parentfirstname' => $parent->parent_first_name,
                    'studentfirstname' => $child->first_name,
                    'yeslink' => "https://" . env('TUTOR') . "/accept-waiting-list?url=" . base64_encode('secret=' . $secret . "&waiting_id=" . $waiting->id),
                    'nolink' => "https://" . env('TUTOR') . "/reject-waiting-list?url=" . base64_encode('secret=' . $secret . "&waiting_id=" . $waiting->id),
                ];
                $this->sendEmail($params['email'], "waiting-list-parent-offer-reminder-email", $params);

                $sms_params = [
                    'name' => $parent->parent_name,
                    'phone' => $parent->parent_phone,
                ];
                $body = "Hi " . $parent->parent_first_name . ", just a reminder that we have a great tutor for " . $child->first_name . " ready to go!  Please check your email for details or to opt out - Team Alchemy";
                $this->sendSms($sms_params, $body);

                $waiting->update(['reminder' => 1]);
                continue;
            }

            $waiting = WaitingLeadOffer::where('job_id', $job->id)->where('status', 0)->where('reminder', 1)
                ->whereRaw("TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 48")->orderBy('id', 'DESC')->first();
            if (!empty($waiting)) {
                $tutor = $waiting->tutor;
                $secret = sha1($waiting->id . env('SHARED_SECRET'));
                $subject = "Re. your recent interest in taking on a student";
                $body = "Hi " . $tutor->first_name . ",<br /><br />";
                $body .= "You recently expressed interest in working with the following student:<br><br>";
                $body .= "Name: " . $child->child_name . "<br/>";
                $body .= "Grade: " . $child->child_year . "<br/>";
                $body .= "Subject: " . $job->subject . "<br/>";
                if ($job->session_type_id == 1) $body .= "Location: " . $job->location . "<br />";
                else $body .= "Online <br/>";
                $body .= "<br>Unfortunately the parent has been unresponsive to our proposal, so will not be going ahead with lessons at this time.<br><br>Don't be disappointed: we have plenty of other opportunities that you would be perfect for in the jobs feed!<br><br>If you have any questions please let us know.<br><br>Team Alchemy";
                $this->sendEmail($tutor->tutor_email, $subject, null, $body);

                $waiting->update(['status' => 1]);
                continue;
            }


            $waiting = WaitingLeadOffer::where('job_id', $job->id)->orderBy('id', 'DESC')->first();
            $flag = false;
            if (!empty($waiting)) {
                $created_at = \DateTime::createFromFormat('Y-m-d H:i:s', $waiting->created_at);
                $diff = $today->diff($created_at);
                if ($diff->days >= 21) $flag = true;
            } else {
                if (!empty($job->last_updated_for_waiting_list)) {
                    $created_at = \DateTime::createFromFormat('d/m/Y H:i', $job->last_updated_for_waiting_list);
                    $diff = $today->diff($created_at);
                    if ($diff->days >= 21) $flag = true;
                } else $flag = true;
            }
            if (!!$flag) {
                $job->update([
                    'job_status' => 2,
                    'last_updated_for_waiting_list' => '',
                    'reason' => "Didn't have any applications in 3 weeks."
                ]);
                $child->update(['child_status' => 0]);
                $this->addStudentHistory([
                    'child_id' => $child->id,
                    'comment' => "Sent student to inactive. Reason: Didn't have any applications in 3 weeks for waiting lists."
                ]);
            }
        }
    }
}
