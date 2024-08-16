<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Session;
use App\Trait\Sessionable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ConfirmSession extends Component
{
    use Sessionable;

    public $session;
    
    public function mount()
    {
        $url = request()->query('key') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = unserialize(base64_decode($url));
            if (!empty($details)) {
                $first_session_id = $details['first_session_id'] ?? '';
                if (!empty($first_session_id)) {
                    $this->session = Session::find($first_session_id);
                    if (!empty($this->session) && empty($this->session->session_next_session_id)) $flag = true;
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        
        // $this->session = Session::find(42980);
    }

    public function confirmSecondSession($session_date, $session_time) {
        try {
            if (empty($session_date) || empty($session_time)) throw new \Exception("Please select session date and time.");
            $session = $this->session;
            if (!$session->session_is_first) throw new \Exception("It is not first session.");

            $parent = $session->parent;
            $child = $session->child;
            $tutor = $session->tutor;
            $this->makeChildActive($child->id);
            $this->addSession([
                'type' => 'second',
                'session_date' => $session_date,
                'session_time' => $session_time,
                'prev_session_id' => $session->id,
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
        return view('livewire.tutor.pages.confirm-session');
    }
}
