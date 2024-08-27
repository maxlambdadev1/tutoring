<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Tutor;
use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class OptOut extends Component
{
    use Functions;

    public $tutor;
    public $break_day;
    public $reason;

    public function mount() {        
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $tutor_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($tutor_id)) {
                        $secret_origin = sha1($tutor_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                           $this->tutor = Tutor::find($tutor_id);
                            if (!empty($this->tutor)) $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $this->tutor = Tutor::find(1062);
    }
    
    public function setBreakDay() {
        try {
            if (empty($this->break_day) || empty($this->reason)) throw new \Exception("Please input the date and reason");
            
            $break_day = date('d/m/Y', strtotime("+" . $this->break_day . " week"));
            $this->tutor->update([
                'accept_job_status' => 0,
                'break_date' => $break_day,
            ]);
            $this->addTutorHistory([
                'tutor_id' => $this->tutor->id,
                'comment' => "Tutor has opted out of student offers for " . $this->break_day . " weeks (until " . $break_day . ") . Reason: " . $this->reason
            ]);

            return $break_day;
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
        return view('livewire.tutor.pages.opt-out');
    }
}
