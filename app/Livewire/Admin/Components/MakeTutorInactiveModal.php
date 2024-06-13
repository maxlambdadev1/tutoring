<?php

namespace App\Livewire\Admin\Components;

use App\Trait\Functions;
use Livewire\Component;
use App\Models\Tutor;
use App\Models\TutorInactiveSchedule;
use App\Trait\Mailable;

class MakeTutorInactiveModal extends Component
{
    use Functions, Mailable;
    public $tutor;

    public function mount($tutor_id) {
        $this->tutor = Tutor::find($tutor_id);
    }

    /**
     * @param $tutor_id, $is_now: true or false, $schedule_date : '25/05/2024'
     */
    public function makeTutorInactive($tutor_id, $is_now, $schedule_date, $reason)
    {
        try {
            $tutor = Tutor::find($tutor_id);

            if ($is_now) {
                $tutor->update(['tutor_status' => 0]);
                $comment = "Sent to inactive. Reason: " . $reason;
                if ($tutor->state == 'QLD') {
                    $params = ['tutor_name' => $tutor->tutor_name];
                    $this->sendEmail($tutor->tutor_email, 'inactive-qld-tutor-email', $params);
                }
            } else {
                if (!empty($schedule_date)) {
                    $timestamp = \DateTime::createFromFormat('d/m/Y', $schedule_date)->getTimestamp();
                    if (!$timestamp) throw new \Exception('Select valid date');

                    TutorInactiveSchedule::updateOrCreate(
                        ['tutor_id' => $tutor->id],
                        ['timestamp' => $timestamp]
                    );
                }
                $comment = "Scheduled to send to inactive for " . $schedule_date . " Reason: " . $reason;;
            }

            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The tutor is now inactive!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.make-tutor-inactive-modal');
    }
}
