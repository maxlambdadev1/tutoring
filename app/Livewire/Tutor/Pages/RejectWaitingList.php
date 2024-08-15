<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\WaitingLeadOffer;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class RejectWaitingList extends Component
{
    use Mailable;

    public $child;
    public $waiting_list;

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
                    $waiting_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($waiting_id)) {
                        $secret_origin = sha1($waiting_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $this->waiting_list = WaitingLeadOffer::find($waiting_id);
                            if (!empty($this->waiting_list) && $this->waiting_list->status == 0) $flag = true;
                        }
                    }
                }
            }
        }
        // $this->waiting_list = WaitingLeadOffer::find(4);
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        else {
            $this->waiting_list->update(['status' => 1]);

            $job = $this->waiting_list->job;
            if (!empty($job) && $job->job_status == 3) {
                $this->child = $job->child ?? null;
                $job->update([
                    'job_status' => 2,
                    'last_updated_for_waiting_list' => '',
                    'reason' => 'No longer require a tutor(in the apply email)'
                ]);

                $tutor = $this->waiting_list->tutor;
                if (!empty($tutor)) {
                    $subject = 'Re. your recent interest in taking on a student';
                    $body = "Hi " . $tutor->first_name . ", <br /><br />";
                    $body .= "You recently expressed interest in working with the following student:<br><br>";
                    $body .= "Name: " . $this->child->child_name . "<br />";
                    $body .= "Grade: " . $this->child->child_year . " <br />";
                    $body .= "Subject: " . $job->subject . " <br />";
                    if ($job->session_type_id == 1) $body .= "Location: " . $job->location . " <br />";
                    else $body .= "Location: Online <br />";
                    $body .= "<br>Unfortunately the parent has decided not to go ahead with lessons at this time.<br><br>Don't be disappointed: we have plenty of other opportunities that you would be perfect for in the jobs feed!<br><br>If you have any questions please let us know.<br><br>Team Alchemy";

                    $this->sendEmail($tutor->tutor_email, $subject, null, $body);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.reject-waiting-list');
    }
}
