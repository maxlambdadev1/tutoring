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
                $this->sendEmail($parent->parent_email, 'online-tutoring-offer-email', $params); //check

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
                $this->sendEmail($parent->parent_email, 'parent-welcome-call-email', $params);

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

                $params = [
                    'studentfirstname' => $child_name_arr[0],
                    'parentfirstname' => $parent->parent_first_name,
                    'adminfirstname' => $admin_name_arr[0],
                    'adminname' => $admin->admin_name
                ];
                $this->sendEmail($parent->parent_email, 'send-to-waiting-list-notification-email', $params);

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
