<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Session;
use App\Trait\Sessionable;

class DeleteSessionModal extends Component
{
    use Sessionable;
    
    public $session;

    public function mount($ses_id) {
        $this->session = Session::find($ses_id);
    }

    public function render()
    {
        return view('livewire.admin.components.delete-session-modal');
    }
    
    public function deleteSession($ses_id, $reason, $is_student_send_to_inactive, $delete_student_reason, $followup, $disable_future_follow_up_reason) {
        try {
            $this->deleteSessionFromSessionId($ses_id, $reason, $is_student_send_to_inactive, $delete_student_reason, $followup, $disable_future_follow_up_reason);

            $this->session = $this->session->fresh();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The session was successfuly deleted.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
