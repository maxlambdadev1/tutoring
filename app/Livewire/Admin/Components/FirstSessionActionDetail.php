<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithParents;
use App\Trait\Sessionable;

class FirstSessionActionDetail extends Component
{
    use Functions, Mailable, WithParents, Sessionable;

    public $type = "";
    public $session;

    public function mount($ses_id, $type = "") {
        $this->session = Session::find($ses_id);
        $this->type = $type;
    }

    public function render()
    {
        $session = $this->session;

        return view('livewire.admin.components.first-session-action-detail', compact('session'));
    }
    
    public function addComment($ses_id, $comment) {
        if (!empty($ses_id) && !empty($comment)) {
            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'session_id' => $ses_id
            ]);
            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    /**
     * @param $session_date : '25/05/2024', $session_time1 : '10:30 PM'
     */
    public function editSession($ses_id, $session_date, $session_time1) {
        try {
            if (empty($session_date) || empty($session_time1)) throw new \Exception('Please select session date and time.');

            $session_time = Carbon::createFromFormat('h:i A', $session_time1)->format('H:i');
            $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
            $ses_date = \DateTime::createFromFormat('d/m/Y H:i', $session_date . ' ' . $session_time);
            if ($today->getTimestamp() >= $ses_date->getTimestamp()) $session_status = 1; //unconfirmed session
            else $session_status = 3; //scheduled session.

            $session = Session::find($ses_id);
            $session->update([
                'session_date' => $session_date,
                'session_time' => $session_time,
                'session_status' => $session_status
            ]);
            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The session was successfuly edited'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function addPenalty($ses_id) {
        try {
            $session = Session::find($ses_id);
            $session->update(['session_penalty' => 5]);

            $sms_params = [
                'name' => $session->tutor->tutor_name,
                'phone' => $session->tutor->tutor_phone
            ];
            $params = [
                'sessiondate' => $session->session_date,
                'studentname' => $session->child->child_name
            ];
            $this->sendSms($sms_params, 'add-penalty-notification-to-tutor-sms', $params);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Added $5 penalty.'
            ]);
            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Penalty successfully added'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function tutorUnconfirmedWarningEmail($ses_id) {
        try {
            $session = Session::find($ses_id);

            $params = [
                'tutorfirstname' => $session->tutor->first_name,
                'studentname' => $session->child->child_name,
                'date' => $session->session_date,
            ];
            $this->sendEmail($session->tutor->tutor_email, 'tutor-unconfirmed-warning-email', $params);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Sent penalty warning email to ' . $session->tutor->tutor_name,
            ]);
            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Sent penalty warning email to the tutor.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function tutorUnconfirmedWarningSms($ses_id) {
        try {
            $session = Session::find($ses_id);

            $sms_params = [
                'name' => $session->tutor->tutor_name,
                'phone' => $session->tutor->tutor_phone
            ];
            $params = [
                'tutorfirstname' => $session->tutor->first_name,
                'studentname' => $session->child->child_name,
                'sessiondate' => $session->session_date,
            ];
            $this->sendSms($sms_params, 'tutor-unconfirmed-warning-sms', $params);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Sent penalty warning sms to ' . $session->tutor->tutor_name,
            ]);
            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Sent penalty warning sms to the tutor.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
        
    public function makeSessionNotContinuing1($ses_id) {
        try {
            $this->makeSessionNotContinuing($ses_id);

            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The session was transfered to not continuing'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * @param $ses_id, $session_date = 25/05/2024, $session_time = 11:30 AM, $payment_info_email = true or false
     */
    public function addSecondSession($ses_id, $session_date, $session_time, $payment_info_email) {
        try { 
            $session = Session::find($ses_id);

            $post = [
                'type' => 'second',
                'session_date' => $session_date,
                'session_time' => $session_time,
                'prev_session_id' => $session->id,
                'payment_info' => $payment_info_email
            ];

            $this->addSession($post);

            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The session was added!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * @param $ses_id, $payment_info_email = true or false, $first_session_info_email = true or false
     */
    public function sendEmailToParent($ses_id, $payment_info_email, $first_session_info_email) {
        try { 
            $session = Session::find($ses_id);

            if (!empty($payment_info_email)) {
                $this->sendPaymentInfoEmailToParent($session->id);
            }
            
            if (!empty($first_session_info_email)) {
                $this->sendFirstSessionFollowupEmailToParent($session->id);
            }

            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Email sent!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function firstSessionTutorUpdate($ses_id) {
        try { 
            $session = Session::find($ses_id);

            $sms_params = [
                'name' => $session->tutor->tutor_name,
                'phone' => $session->tutor->tutor_phone
            ];
            $params = [
                'tutorfirstname' => $session->tutor->first_name,
                'studentname' => $session->child->child_name
            ];
            $this->sendSms($sms_params, 'first-session-tutor-update-sms', $params);

            $this->addSessionHistory([
                'session_id' => $ses_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Sent first session update sms to tutor'
            ]);

            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Update SMS sent to tutor'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
