<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\TutorApplication;
use App\Models\TutorApplicationReference;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class Reference extends Component
{
    public $email;
    public $application_id;
    public $reason;
    public $comment;

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $email = explode('=', $exp[1])[1] ?? '';
                    if (!empty($email)) {
                        $secret_origin = sha1($email . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $this->email = $email;
                            $this->application_id = explode('=', $exp[2])[1] ?? '';
                            $this->reason = explode('=', $exp[3])[1] ?? '';
                            if (!empty($this->application_id) && !empty($this->reason)) $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));

        // $this->email = 'aaa.com';
        // $this->application_id = 123;
        // $this->reason = 'no';

        if ($this->reason == 'no') $this->insertTutorReference();
    }

    public function insertTutorReference() {
        try {
            $check_reference = TutorApplication::where('id', $this->application_id)->where(function ($query) {
                $query->where('tutor_email1', $this->email)->orWhere('tutor_email2', $this->email);
            })->first(); 
            if (empty($check_reference)) throw new \Exception('There is no the application.');

            if ($this->reason == 'no') {
                $reference_reason_check = TutorApplicationReference::where('application_id', $this->application_id)->where('reason', 'ok')->first();
                if (!empty($reference_reason_check)) TutorApplication::where('id', $this->application_id)->update(['reference_respond_status' => 1]);
            }
            $app_reference =  TutorApplicationReference::where('application_id', $this->application_id)->where('reference_email', $this->email)->first();
            if (!empty($app_reference)) throw new \Exception('The application reference is already existed.');

            $reason = empty($this->comment) ? 'ok' : $this->comment;
            TutorApplicationReference::create([
                'application_id' => $this->application_id,
                'reference_email' => $this->email,
                'reason' => $reason,
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
        return view('livewire.tutor.pages.reference');
    }
}
