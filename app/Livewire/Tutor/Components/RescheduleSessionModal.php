<?php

namespace App\Livewire\Tutor\Components;

use App\Trait\Sessionable;
use Carbon\Carbon;
use App\Models\Session;
use App\Models\SessionFilter;
use App\Models\Child;
use App\Models\Notification;
use App\Models\SessionReschedule;
use Livewire\Component;

class RescheduleSessionModal extends Component
{
    use Sessionable;

    public $session;
    public $no_session_scheduled = false;
    public $no_session_scheduled_reason;
    public $no_session_scheduled_additional_info;

    public function mount($session_id)
    {
        $this->session = Session::find($session_id);
    }

    public function rescheduleLesson($session_date, $session_time)
    {
        try {
            if (!!$this->no_session_scheduled) {
                if (empty($this->no_session_scheduled_reason) || empty($this->no_session_scheduled_additional_info)) throw new \Exception("Please add the reason");

                $reason = !empty($this->no_session_scheduled_reason) ? $this->no_session_scheduled_reason : $this->no_session_scheduled_additional_info;
                $this->session->update([
                    'session_status' => 4,
                    'session_reason' => $reason,
                    'session_last_changed' => date('d/m/Y H:i')
                ]);
            } else {
                if (empty($session_date) || empty($session_time)) throw new \Exception("Please select all data correctly.");

                $tutor = auth()->user()->tutor;

                $session_time = Carbon::createFromFormat('h:i A', $session_time)->format('H:i');
                $session = $this->session;

                $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
                $ses_date = \DateTime::createFromFormat('d/m/Y H:i', $session_date . ' ' . $session_time);
                if ($today->getTimestamp() >= $ses_date->getTimestamp()) $session_status = 1; //unconfirmed session
                else $session_status = 3; //scheduled session.

                $this->addSessionHistory([
                    'session_id' => $session->id,
                    'comment' => $tutor->tutor_name . " has rescheduled this session from " . $session->session_date . " " . $session->session_time . " to " . $session_date . " " . $session_time,
                ]);
                $session->update([
                    'session_date' => $session_date,
                    'session_time' => $session_time,
                    'session_status' => $session_status,
                    'session_reason' => '',
                    'session_last_changed' => date('d/m/Y H:i')
                ]);
                SessionReschedule::create([
                    'session_id' => $session->id,
                    'old_date' => $session->session_date,
                    'old_time' => $session->session_time,
                    'new_date' => $session_date,
                    'new_time' => $session_time,
                    'date' => date('d/m/Y H:i')
                ]);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Updated successfully'
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
        return view('livewire.tutor.components.reschedule-session-modal');
    }
}
