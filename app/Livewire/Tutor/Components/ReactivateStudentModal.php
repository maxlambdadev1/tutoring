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

class ReactivateStudentModal extends Component
{
    use Sessionable;

    public $child;

    public function mount($child_id) {
        $this->child = Child::find($child_id);
    }

    public function reactivateAndScheduleLesson($session_date, $session_time) {
        try {
            if (empty($session_date) || empty($session_time)) throw new \Exception("Please select all data correctly.");

            $session_time = Carbon::createFromFormat('h:i A', $session_time)->format('H:i');
            $this->child->update(['child_status' => 1]);
            $tutor = auth()->user()->tutor ?? '';
            $session = Session::where('tutor_id', $tutor->id)->where('child_id', $this->child->id)->orderBy('id', 'DESC')->first();
            if (!empty($session)) {
                if ($session->session_is_first == 1 && empty($session->session_next_session_id)) throw new \Exception("We are currently awaiting confirmation from this student’s parents of your second session. If you have confirmed it with them directly please let us know and we can add it for you.");

                if ($session->session_status == 1 || $session->session_status == 3) {
                    $session->update([
                        'session_date' => $session_date,
                        'session_time' => $session_time,
                        'session_status' => 3,
                        'session_reason' => 'Reactivated the student and scheduled session',
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
                } else {
                    $this->addSession([
                        'type' => 'regular',
                        'prev_session_id' => $session->id,
                        'session_date' => $session_date,
                        'session_time' => $session_time,
                    ]);

                    Notification::create([
                        'user_id' => $tutor->user->id,
                        'notification_level' => 0,
                        'notification_text' => 'You added a new session with ' . $this->child->child_name,
                    ]);
                    SessionFilter::where('tutor_id', $tutor->id)->where('parent_id', $this->child->parent->id)->where('child_id', $this->child->id)->delete();

                    //send email to info@alchemytuition.com.au
                }
            } else throw new \Exception("There is no lesson for the student.");

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
        return view('livewire.tutor.components.reactivate-student-modal');
    }
}
