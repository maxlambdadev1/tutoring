<?php

namespace App\Livewire\Tutor\Sessions;

use App\Models\Session;
use App\Models\SessionFilter;
use App\Models\ThirdpartyOrganisation;
use App\Models\TutorFirstSession;
use App\Trait\Functions;
use Carbon\Carbon;
use App\Trait\Sessionable;
use App\Trait\TutoringXero;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class ConfirmSession extends Component
{
    use Sessionable, Functions, TutoringXero;

    public $session;
    public $session_hours = 0;
    public $session_minutes = 0;
    public $session_type_id;
    public $feedback;
    public $notes_for_yourself;
    public $session_date;
    public $session_time;
    public $no_session_scheduled = false;
    public $no_session_scheduled_reason = '';
    public $no_session_scheduled_additional_info = '';

    public $question_1;
    public $question_2;
    public $question_3;
    public $question_4;

    public function mount($session_id)
    {
        $this->session = Session::find($session_id);
        if (empty($this->session) || $this->session->session_status != 1) $this->redirect("/sessions/unconfirmed-sessions");
    }

    public function confirmSession($session_date, $session_time, $session_overall_rating, $session_engagement_rating, $session_understanding_rating)
    {
        try {
            if (empty($this->session_type_id)) throw new \Exception("Please select sessin type");

            $session_length = $this->session_hours + $this->session_minutes;
            $session = $this->session;
            $parent = $session->parent;
            $child = $session->child;
            $tutor = auth()->user()->tutor;

            $this->makeChildActive($child->id);
            $session_price = $this->calcSessionPrice($parent->id, $this->session_type_id);
            $tutor_price = $this->calcTutorPrice($tutor->id, $parent->id, $child->id, $this->session_type_id);
            $payment_status = $this->checkPaymentStatus($session->id);
            if (!empty($this->no_session_scheduled)) $reason = !empty($this->no_session_scheduled_reason) ? $this->no_session_scheduled_reason : $this->no_session_scheduled_additional_info;
            else $reason = '';

            $session->update([
                'session_length' => $session_length,
                'session_status' => 2,
                'session_reason' => $reason,
                'session_charge_status' => $payment_status,
                'session_overall_rating' => $session_overall_rating,
                'session_engagement_rating' => $session_engagement_rating,
                'session_understanding_rating' => $session_understanding_rating,
                'session_feedback' => $this->feedback,
                'session_tutor_notes' => $this->notes_for_yourself,
                'session_last_changed' => date('d/m/Y H:i'),
                'type_id' => $this->session_type_id,
                'session_price' => $session_price,
                'session_tutor_price' => $tutor_price,
            ]);

            if (empty($this->no_session_scheduled)) {
                $this->addSession([
                    'type' => 'regular',
                    'prev_session_id' => $session->id,
                    'session_date' => $session_date,
                    'session_time' => $session_time,
                ]);
            }
            $this->calculateSessions($session->id);

            if (empty($tutor->online_acceptable_status)) {
                $online_limit = $this->getOption('online-limit');
                $sessions = Session::where('tutor_id', $tutor->id)->where('session_status', 2)->count();
                if ($sessions >= $online_limit) {
                    $tutor->update(['online_acceptable_status' => 1]);
                    $params = [
                        'email' => $tutor->tutor_email,
                        'onlinelimitnumber'  => $online_limit,
                    ];
                    $this->sendEmail($params['email'], 'online-session-accept-email', $params);
                }
            }
            if (empty($tutor->experienced)) {
                $experience_limit = $this->getOption('experience-limit');
                $sessions = Session::where('tutor_id', $tutor->id)->where('session_status', 2)->count();
                if ($sessions >= $experience_limit) {
                    $tutor->update(['experienced' => 1]);
                }
            }

            // $this->updateInXero();

            $date = \DateTime::createFromFormat('d/m/Y H:i', $session->session_last_changed);
            $date->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            $date->add(new \DateInterval('P1D'));
            $secret = sha1($session->id . env('SHARED_SECRET'));
            $params = [
                'length' => $session_length,
                'date' => $session->session_date,
                'email' => $parent->parent_email,
                'parentfirstname' => $parent->parent_first_name,
                'studentfirstname' => $child->first_name,
                'studentname' => $child->child_name,
                'tutorname' => $tutor->tutor_name,
                'engagementrating' => $session_engagement_rating,
                'understandingrating' => $session_understanding_rating,
                'overallrating' => $session_overall_rating,
                'feedback' => $this->feedback,
                'feedbackurl' => "https://" . env('TUTOR') . "/feedback?url=" . base64_encode("secret=" . $secret . "&session_id=" . $session->id . "&tutor_name=" . $tutor->tutor_name),
                'nextdate' => $session_date,
                'nexttime' => $session_time,
                'tutorphone' => $tutor->tutor_phone,
                'price' => $session_price * $session_length ?? '',
                'chargedate' => $date->format('d/m/Y'),
                'chargetime' => $date->format('h:i A'),
            ];

            if (!empty($parent->thirdparty_org_id) && !empty($parent->thirdparty_org)) {
                //add bcc to primary contact email
                $this->sendEmail($params['email'], "third-party-parent-regular-session", $params);
            } else {
                if (!empty($this->no_session_scheduled)) $this->sendEmail($params['email'], "parent-regular-session-paid-no-next-email", $params);
                else  $this->sendEmail($params['email'], "parent-regular-session-paid-email", $params);
            }

            $params['email'] = $tutor->tutor_email;
            if (!empty($this->no_session_scheduled)) $this->sendEmail($params['email'], "tutor-session-confirmation-no-next-email", $params);
            else  $this->sendEmail($params['email'], "tutor-session-confirmation-email", $params);

            SessionFilter::where('tutor_id', $tutor->id)->where('parent_id', $parent->id)->where('child_id', $child->id)->delete();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You confirmed this session.'
            ]);
            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function confirmFirstSession($session_date, $session_time)
    {
        try {
            if (empty($this->session_type_id)) throw new \Exception("Please select sessin type");

            $session_length = $this->session_hours + $this->session_minutes;
            $session = $this->session;
            $parent = $session->parent;
            $child = $session->child;
            $tutor = auth()->user()->tutor;

            $this->makeChildActive($child->id);
            $session_price = $this->calcSessionPrice($parent->id, $this->session_type_id);
            $tutor_price = $this->calcTutorPrice($tutor->id, $parent->id, $child->id, $this->session_type_id);
            $payment_status = $this->checkPaymentStatus($session->id);

            $session->update([
                'session_length' => $session_length,
                'session_status' => 2,
                'session_first_question1' => $this->question_1,
                'session_first_question2' => $this->question_2,
                'session_first_question3' => $this->question_3,
                'session_first_question4' => $this->question_4,
                'type_id' => $this->session_type_id,
                'session_next_session_tutor_date' => $session_date,
                'session_next_session_tutor_time' => $session_time,
                'session_price' => $session_price,
                'session_tutor_price' => $tutor_price,
                'session_charge_status' => $payment_status,
                'session_last_changed' => date('d/m/Y H:i'),
            ]);

            TutorFirstSession::where('tutor_id', $tutor->id)->whereNot('status', 5)->update(['status' => 4]);
            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'comment' => "Changed status to 'Awaiting follow up' after confirming their first session"
            ]);
            $this->calculateSessions($session->id);

            // $this->updateInXero();

            $date = \DateTime::createFromFormat('d/m/Y H:i', $session->session_last_changed);
            $date->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            $date->add(new \DateInterval('P1D'));
            $secret = sha1($session->id . env('SHARED_SECRET'));
            $params = [
                'email' => $parent->parent_email,
                'parentfirstname' => $parent->parent_first_name,
                'tutorfirstname' => $tutor->first_name,
                'studentname' => $child->child_name,
                'STUDENTNAME' => strtoupper($child->first_name),
                'q1' => $this->question_1,
                'q2' => $this->question_2,
                'q3' => $this->question_3,
                'q4' => $this->question_4,
                'link' => $this->createNextSessionLink($session->id),
            ];

            $this->sendEmail($params['email'], "parent-after-first-session-email", $params);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You confirmed this session.'
            ]);
            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function render()
    {
        if (!empty($this->session->session_is_first)) return view('livewire.tutor.sessions.confirm-first-session');
        else return view('livewire.tutor.sessions.confirm-session');
    }
}
