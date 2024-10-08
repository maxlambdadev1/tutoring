<?php

namespace App\Trait;

use App\Models\AlchemyParent;
use Illuminate\Support\Facades\DB;
use App\Models\ConversionTarget;
use App\Models\FirstSessionTarget;
use App\Models\TutorFirstSession;
use App\Models\GlassdoorReview;
use App\Models\Task;
use App\Models\Notification;
use App\Models\SessionProgressReport;
use App\Models\ParentPodiumReview;
use App\Models\ReplacementTutor;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\SecondSession;
use App\Models\Session;
use App\Trait\Functions;
use App\Trait\WithParents;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use App\Trait\PriceCalculatable;
use Carbon\Carbon;

trait Sessionable
{

    use Functions, WithParents, PriceCalculatable, Mailable, WithLeads;

    public const NO_SESSION_FILTER_ARRAY = [
        1 => 'Uncategorized',
        2 => 'Waiting to hear back from tutor',
        3 => 'Waiting to hear back from parent',
        4 => 'Disregard',
        5 => 'Monitor',
        6 => 'Rescue'
    ];
    public const TUTOR_FIRST_SESSION_STATUS = [
        1 => 'Scheduling call',
        2 => 'Call booked',
        3 => 'Called',
        4 => 'Awaiting follow up',
        5 => 'Closed'
    ];

    /**
     * If tutor didn't have a session,  record the current date to TutorFirstSession table.
     */
    public function checkTutorFirstSession($tutor_id)
    {
        $sessions_length = Session::where('tutor_id', $tutor_id)->count();
        if ($sessions_length < 1) {
            TutorFirstSession::create([
                'tutor_id' => $tutor_id,
                'status' => 1,
                'date_created' => date('d/m/Y H:i'),
                'date_last_update' => date('d/m/Y H:i')
            ]);

            $param = [
                'tutor_id' => $tutor_id,
                'comment' => "Added to first session list. Set status 'Scheduling call'"
            ];
            $this->addTutorHistory($param);
        }
    }

    public function addConversionTarget($job_id, $session_id, $converted_by = 'admin')
    {
        $datetime = new \DateTime('Australia/Sydney');
        ConversionTarget::create([
            'job_id' => $job_id,
            'session_id' => $session_id,
            'conversion_date' => $datetime->format('d/m/Y'),
            'converted_by' => $converted_by
        ]);
    }

    public function addFirstSessionTarget($session_id)
    {
        $datetime = new \DateTime('Australia/Sydney');
        FirstSessionTarget::create([
            'session_id' => $session_id,
            'session_date' => $datetime->format('d/m/Y'),
        ]);
    }

    public function deleteSessionFromSessionId($ses_id, $reason, $is_student_send_to_inactive, $delete_student_reason, $followup, $disable_future_follow_up_reason)
    {
        try {
            if (empty($reason)) throw new \Exception('Input the reason.');
            if (!!$is_student_send_to_inactive && empty($followup)) throw new \Exception('Select follow up.');

            $session = Session::find($ses_id);
            $session->update([
                'session_status' => 6,
                'session_last_changed' => date('d/m/Y H:i'),
                'session_next_session_id' => NULL,
            ]);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Deleted this session. Reason: ' . $reason,
            ]);

            if (!!$is_student_send_to_inactive && !empty($followup)) {
                $this->makeStudentInactive($session->child->id, $delete_student_reason, $followup, $disable_future_follow_up_reason);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function makeSessionNotContinuing($ses_id)
    {
        try {
            $session = Session::find($ses_id);
            $session->update([
                'session_status' => 5,
                'session_charge_status' => 'Not continuing'
            ]);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Changed status to not continuing.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $post = [
     *      'type' => first, second or regular,
     *      'session_date' => 25/05/2024,
     *      'session_time' => 11:30 AM,
     *      'session_type_id' => 1 or 2,
     *      'prev_session_id' => null or session id,
     *      'subject' => subject,
     *      'child_id' => child_id,
     *      'tutor_id' => tutor_id,
     *      'payment_info' => true or false //optional
     * ]
     */
    public function addSession($post)
    {
        try {
            if ((empty($post['type']) || empty($post['session_date']) ||  empty($post['session_time'])) || $post['type'] == 'first' && (empty($post['subject']) || empty($post['child_id']) || empty($post['tutor_id'])) || $post['type'] !== 'first' && empty($post['prev_session_id'])) throw new \Exception('Input all data correctly');

            $is_first = 0;
            $prev_session_id = null;
            $echo = false;
            $session_status = 3;
            $session_subject = isset($post['subject']) ? $post['subject'] : null;
            $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
            $session_date = \DateTime::createFromFormat('d/m/Y H:i', $post['session_date'] . ' ' . Carbon::createFromFormat('h:i A', $post['session_time'])->format('H:i'));
            $session_date->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            if ($today->getTimestamp() >= $session_date->getTimestamp()) {
                $session_status = 1;
            }
            $session_type_id = isset($post['session_type_id']) ? $post['session_type_id'] : 1;
            $child_id = isset($post['child_id']) ? $post['child_id'] : null;
            $tutor_id = isset($post['tutor_id']) ? $post['tutor_id'] : null;
            if ($post['type'] == 'first') { //first session
                $is_first = 1;
                if (empty($child_id)) throw new \Exception('The student not found');
                $child = Child::find($child_id);
                $parent = $child->parent;
                $tutor = Tutor::find($tutor_id);
            } else { //second or regular session
                $prev_session_id = $post['prev_session_id'] ?? null;
                $prev_session = Session::find($prev_session_id);
                if (empty($prev_session)) throw new \Exception('Previous session not found');
                $session_subject = $prev_session->session_subject;
                $session_type_id = $prev_session->type_id ?? 1;
                $child = $prev_session->child;
                $child_id = $child->id;
                $parent = $child->parent;
                $tutor = $prev_session->tutor;
            }
            if (empty($tutor->accept_job_status)) throw new \Exception("The tutor don't have permission to create a session.");

            $session_price = $this->calcSessionPrice($parent->id, $session_type_id);
            $tutor_price = $this->calcTutorPrice($tutor->id, $parent->id, $child->id, $session_type_id);
            /** add logic for stripe user */

            $session = Session::create([
                'session_status' => $session_status,
                'tutor_id' => $tutor->id,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'session_date' => $post['session_date'],
                'session_time' => Carbon::createFromFormat('h:i A', $post['session_time'])->format('H:i'),
                'session_subject' => $session_subject,
                'session_is_first' => $is_first,
                'session_previous_session_id' => $prev_session_id,
                'session_price' => $session_price,
                'session_tutor_price' => $tutor_price,
                'session_last_changed' => date('d/m/Y H:i'),
                'type_id' => $session_type_id
            ]);

            if ($is_first == 1) $this->addFirstSessionTarget($session->id);
            else {
                $prev_session->update(['session_next_session_id' => $session->id]);

                if ($post['type'] == 'second') {
                    $params = [
                        'tutorname' => $tutor->tutor_name,
                        'tutorfirstname' => $tutor->first_name,
                        'tutoremail' => $tutor->user->email,
                        'tutorphone' => $tutor->tutor_phone,
                        'tutoraddress' => $tutor->address . ', ' . $tutor->suburb . ', ' . $tutor->state . ', ' . $tutor->postcode,
                        'tutorabn' => $tutor->ABN,
                        'date' => $prev_session->session_date,
                        'time' => $prev_session->session_time,
                        'length' => $prev_session->session_length,
                        'amount' => $prev_session->session_tutor_price,
                        'studentname' => $child->child_name,
                        'parentname' => $parent->parent_name,
                        'parentfirstname' => $parent->parent_first_name,
                        'parentphone' => $parent->parent_phone,
                        'nextsessiondate' => $post['session_date'],
                        'nextsessiontime' => $post['session_time'],
                    ];
                    $this->sendEmail($tutor->user->email, 'tutor-second-session-email', $params);

                    $params['email'] = $parent->parent_email;
                    if (!empty($parent->thirdparty_org_id)) $this->sendEmail($parent->parent_email, 'third-party-parent-after-second-session-confirmation-email', $params);
                    else $this->sendEmail($parent->parent_email, 'parent-after-second-session-confirmation-email', $params);

                    if (!empty($post['payment_info'])) {
                        $this->sendPaymentInfoEmailToParent($prev_session->id);
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendPaymentInfoEmailToParent($ses_id)
    {
        try {
            $session = Session::find($ses_id);
            $parent = $session->parent;
            $child = $session->child;
            $tutor = $session->tutor;

            $params = [
                'amount' => $session->session_tutor_price,
                'length' => $session->session_length,
                'studentname' => $child->child_name,
                'date' => $session->session_date,
                'email' => $tutor->user->email,
            ];
            $this->sendCcEmail($parent->id, $child->id);

            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Sent 'Request payment information' email to parent.",
                'session_id' => $session->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendFirstSessionFollowupEmailToParent($ses_id)
    {
        try {
            $session = Session::find($ses_id);
            $parent = $session->parent;
            $child = $session->child;
            $tutor = $session->tutor;

            $params = [
                'length' => $session->session_length,
                'studentname' => $child->first_name,
                'date' => $session->session_date,
                'parentfirstname' => $parent->parent_first_name,
                'tutorfirstname' => $tutor->first_name,
                'tutorname' => $tutor->tutor_name,
                'email' => $parent->parent_email,
                'STUDENTNAME' => strtoupper($child->first_name),
                'q1' => $session->session_first_question1,
                'q2' => $session->session_first_question2,
                'q3' => $session->session_first_question3,
                'q4' => $session->session_first_question4,
                'link' => $this->createNextSessionLink($session->id)
            ];
            $this->sendEmail($parent->parent_email, 'parent-first-session-followup-email', $params);

            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Sent 'How was your first session?' email to parent.",
                'session_id' => $session->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendCcEmail($parent_id, $child_id)
    {
        try {
            $parent = AlchemyParent::find($parent_id);
            $child = Child::find($child_id);
            $admin = auth()->user()->admin;

            $params = [
                'parentfirstname' => $parent->parent_first_name,
                'studentname' => $child->first_name,
                'link' => $this->setRedirect('https://alchemy.team/paymentcc?email=' . $parent->parent_email),
                'email' => $parent->parent_email,
            ];
            $this->sendEmail($parent->parent_email, 'parent-payment-details-email', $params);

            $this->addParentHistory([
                'author' => $admin->admin_name,
                'comment' => 'Sent request payment email to ' . $parent->parent_email,
                'parent_id' => $parent->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendCcSMS($parent_id, $child_id)
    {
        try {
            $parent = AlchemyParent::find($parent_id);
            $child = Child::find($child_id);
            $admin = auth()->user()->admin;

            $sms_params = [
                'phone' => $parent->parent_phone,
                'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name
            ];
            $params = [
                'parentfirstname' => $parent->parent_first_name,
                'studentname' => $child->first_name,
                'link' => $this->setRedirect('https://alchemy.team/paymentcc?email=' . $parent->parent_email),
            ];
            $this->sendSMS($sms_params, 'parent-payment-details-sms', $params);

            $this->addParentHistory([
                'author' => $admin->admin_name,
                'comment' => 'Sent request payment SMS to ' . $parent->parent_email,
                'parent_id' => $parent->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function createNextSessionLink($ses_id)
    {
        try {
            $session = Session::find($ses_id);

            $link_array = [
                'tutor_id' => $session->tutor_id,
                'parent_id' => $session->parent_id,
                'child_id' => $session->child_id,
                'first_session_id' => $session->id
            ];
            $link = base64_encode(serialize($link_array));

            SecondSession::updateOrCreate([
                'first_session_id' => $session->id,
                'tutor_id' => $session->tutor_id,
                'parent_id' => $session->parent_id,
                'child_id' => $session->child_id,
            ], [
                'unique_link' => $link
            ]);

            return $this->setRedirect('https://alchemy.team/confirm-session?key=' . $link);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * Make the student as active
     * @param int $child_id
     * @return void
     */
    public function makeChildActive($child_id) {
        $child = Child::find($child_id);
        if (!empty($child)) {
            $child->update(['child_status' => 0]);
            $this->addStudentHistory([
                'child_id' => $child_id,
                'comment' => 'Sent student to active since they are confirming a session.'
            ]);
        }
    }

    public function calculateSessions($session_id) {
        $session = Session::find($session_id);
		$ten_confirmed_session_check = false;
        $tutor = $session->tutor;
        $parent = $session->parent;
        $child = $session->child;

        $sessions = Session::where('tutor_id', $tutor->id)->count();
        if ($sessions > 9 && $sessions % 10 == 0) $ten_confirmed_session_check = true;

        $confirmed_sessions = Session::where('tutor_id', $tutor->id)->where('parent_id', $parent->id)->where('child_id', $child->id)
            ->where(function ($query) {
                $query->where('session_status', 2)->orWhere('session_status', 4);
            })
            ->where(function ($query) {
                $query->whereNotNull('session_length')->orWhere('session_status', '>', 0);
            })->count();

        if ($parent->{'5_sessions_podium_review'} == 0 && $confirmed_sessions == 5) {
            $checker_reminder = ParentPodiumReview::where('parent_id', $parent->id)->count();
            if ($checker_reminder < 1) {
                $sms_params = [
                    'name' => $parent->parent_name,
                    'phone' => $parent->parent_phone,
                ];
                //add - send switchboard invitation function

                $parent->update(['5_sessions_podium_review' => 1]);
                ParentPodiumReview::create([
                    'parent_id' => $parent->id
                ]);
            }
        }

        if ($confirmed_sessions > 0 && $confirmed_sessions %10 == 0 && $confirmed_sessions <= 50) {
            $check_report = SessionProgressReport::where('tutor_id', $tutor->id)->where('parent_id', $parent->id)->where('child_id', $child->id)->where('session_count', $confirmed_sessions)->get();
            if (empty($check_report)) {
                $report = SessionProgressReport::create([
                    'tutor_id' => $tutor->id,
                    'parent_id' => $parent->id,
                    'child_id' => $child->id,
                    'session_count' => $confirmed_sessions,
                    'unique_key' => '0',
                    'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);
                $ukey = base64_encode(serialize([
                    'tutor_id' => $tutor->id,
                    'parent_id' => $parent->id,
                    'child_id' => $child->id,
                    'parent_name' => $parent->parent_name,
                    'count' => $confirmed_sessions,
                    'id' => $report->id,
                    'session_type_id' => $session->type_id,
                ]));
                $report->update(['unique_key' => $ukey]);

                Task::create([
                    'tutor_id' => $tutor->id,
                    'task_subject' => 'progress',
                    'task_name' => "Submit progress report for " . $child->child_name,
                    'task_content' => "progress-report?key=" . $ukey,
                    'task_date' => (new \DateTime('now'))->format('d/m/Y H:i'),
                    'task_last_update' => (new \DateTime('now'))->format('d/m/Y H:i'),
                ]);
                Notification::create([
                    'user_id' => $tutor->user->id,
                    'notification_level' => 0,
                    'notification_text' => 'Submit progress report for ' . $child->child_name,
                ]);

                $params = [
                    'email' => $tutor->tutor_email,
                    'studentname' => $child->child_name,
                    'studentfirstname' => $child->first_name,
                    'link' => $this->setRedirect("https://" . env('TUTOR') . "/progress-report?key=" . $ukey)
                ];
                $this->sendEmail($params['email'], "tutor-" . $confirmed_sessions . "-sessions-email", $params);

                return;
            }
        }

        if ($confirmed_sessions >= 5) {
            if (empty($tutor->glassdoor)) {
                GlassdoorReview::create([
                    'tutor_id' => $tutor->id,
                    'date' => date('d/m/Y')
                ]);
                $tutor->update(['glassdoor' => 1]);
            }
        }
    }
}
