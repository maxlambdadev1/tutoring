<?php

namespace App\Trait;

use App\Models\Option;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Trait\Mailable;
use App\Trait\Functions;

trait Automationable
{
    use Mailable, Functions;

    public function findTutorForJob($job_id)
    {
    }

    public function sendOnlineTutoringEmail($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = User::find(auth()->user()->id)->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $params = [];
                $params['username'] = $admin->admin_name;
                $params['userfirstname'] = $admin_name_arr[0];
                $params['useremail'] = $admin->user->email;
                $params['studentfirstname'] = $child_name_arr[0];
                $params['studentname'] = $child_name_arr[0];
                $params['parentfirstname'] = $parent->parent_first_name;
                $params['email'] = $parent->parent_email;
                // $this->sendEmail($params, 'online-tutoring-offer-email'); //check

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'Online option email sent to parent',
                    'author' => $admin->admin_name
                ]);

                return true;
            } else {
                throw new \Exception('The job is not existed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendWelcomeEmailAndSms($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = User::find(auth()->user()->id)->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $params = [];
                $params['username'] = $admin->admin_name;
                $params['userfirstname'] = $admin_name_arr[0];
                $params['useremail'] = $admin->user->email;
                $params['studentname'] = $child_name_arr[0];
                $params['parentfirstname'] = $parent->parent_first_name;
                $params['email'] = $parent->parent_email;
                // $this->sendEmail($params, 'parent-welcome-call-email'); //check

                $params = array(
                    'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                    'phone' => $parent->parent_phone,
                    'body' => "Hi " . $parent->parent_first_name . ", " . $admin_name_arr[0] . " from Alchemy Tuition here.\nJust letting you know I've received your tutor request for " . $child_name_arr[0] . " and am working on matching them up with one of our amazing tutors.\nIf there are any details you'd like to add or discuss further please call me on 1300 914 329.\nAs soon as I have everything organised I will confirm the first lesson with you by SMS and email!\n" . $admin_name_arr[0] . ", Team Alchemy"
                );
                // $this->sendSms($params); //check 

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'Welcome email and sms sent',
                    'author' => $admin->admin_name
                ]);

                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendToWaitingList($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                if ($job->job_status != 0) throw new \Exception('The job is not active.');
                $job->update([
                    'job_status' => 3,
                    'last_updated_for_waiting_list' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);

                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = User::find(auth()->user()->id)->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $subject = "Tutoring for " . $child_name_arr[0];
                $body = "Hi " . $parent->parent_first_name . ",<br><br>" . $admin_name_arr[0] . "from Alchemy here - hope you are well.<br><br>I just wanted to send through an update on organising a tutor for " . $child_name_arr[0] . ".<br><br>Despite our best efforts, we have regretfully not been able to pair them up with a matching tutor and think it would be best to move your request to our priority waiting list.<br><br>From here, if a great matching tutor comes along we will email you to let you know their availabilities and profile, and you can choose if you would like to go ahead with the first lesson. <br><br>I am really sorry that we couldn't confirm a lesson right away - but by keeping %%childfirstname%% on our priority waiting list we will be able to notify you as great tutors become available and give you the opportunity to get started with a tutor then.<br><br>We will keep " . $child_name_arr[0] . " on our priority waiting list for 3 months - you can also request to be removed anytime we send you tutor details.<br><br>If you have any questions please let me know!<br><br>" . $admin->admin_name . "<br>Alchemy Tuition";
                $params['email'] = $parent->parent_email;
                // $this->sendEmail(); //check

                $params = array(
                    'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                    'phone' => $parent->parent_phone,
                    'body' => "Hi " . $parent->parent_first_name . ", please check your email for an update on organising a tutor for " . $child_name_arr[0] . " - Team Alchemy"
                );
                // $this->sendSms($params); //check 

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'The lead is sent to the waiting list',
                    'author' => $admin->admin_name
                ]);

                return true;
            } else {
                throw new \Exception('The job is not existed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
