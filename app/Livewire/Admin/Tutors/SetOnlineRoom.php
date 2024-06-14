<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use App\Models\Tutor;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class SetOnlineRoom extends Component
{
    use Mailable;

    public function render()
    {
        return view('livewire.admin.tutors.set-online-room');
    }

    public function setTutorOnlineRoom($tutor_id, $online_url) {
        try {
            $tutor = Tutor::find($tutor_id);
            $tutor->update([
                'online_url' => $online_url
            ]);
            
            $params = [
                'tutorfirstname' => $tutor->first_name,
                'onlineurl' => $online_url,
                'email' => $tutor->tutor_email
            ];
            $this->sendEmail($tutor->tutor_email, 'tutor-online-classroom-setup-email', $params);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The online url was updated successfully!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
}
