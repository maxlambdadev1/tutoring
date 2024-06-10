<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Session;
use App\Models\TutorConnected;
use App\Trait\Functions;

class ConnectNewTutorForNoScheduledSessionModal extends Component
{
    use Functions;
    public $session;
    public $tutors;

    public function mount($ses_id)
    {
        $this->session = Session::find($ses_id);
        $child = $this->session->child;
        // Get unique tutor IDs directly
        $tutor_ids = Session::where('child_id', $child->id)
            ->groupBy('tutor_id')
            ->pluck('tutor_id'); // Use pluck to get an array of tutor_id values

        // Retrieve all tutors in one query and filter by status
        $tutors = Tutor::whereIn('id', $tutor_ids)
            ->where('tutor_status', 1)
            ->get();

        $this->tutors = $tutors;
    }
    public function render()
    {
        return view('livewire.admin.components.connect-new-tutor-for-no-scheduled-session-modal');
    }

    public function connectNewTutorToSession($ses_id, $connect_tutor_id) {        
        try {
            if (empty($ses_id) || empty($connect_tutor_id)) throw new \Exception('Select fields.');
            
            $session = Session::find($ses_id);
            $check_connected = TutorConnected::where('tutor_id', $session->tutor_id)->where('child_id', $session->child_id)->where('connected_id', $connect_tutor_id)->first();
            if (!empty($check_connected)) throw new \Exception('The tutor already connected.'); 

            TutorConnected::create([
                'tutor_id' => $session->tutor_id,
                'child_id' => $session->child_id,
                'connected_id' => $connect_tutor_id
            ]);

            $connected_tutor = Tutor::find($connect_tutor_id);
            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Added ' . $connected_tutor->tutor_name . ' as connected tutor',
                'session_id' => $session->id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The student is now connected with new tutor'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
