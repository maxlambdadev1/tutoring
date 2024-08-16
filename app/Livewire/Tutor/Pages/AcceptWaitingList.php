<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\JobVisit;
use App\Models\Tutor;
use App\Models\WaitingLeadOffer;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\Sessionable;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class AcceptWaitingList extends Component
{
    use Functions, Mailable, Sessionable;

    public $waiting_lead_offer;
    public $job;
    public $child;
    public $parent;
    public $tutor;
    public $job_availability;

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        $waiting_lead_offer = null;
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
                            /* for testing
                            $waiting_id = 4;
                            $waiting_lead_offer = WaitingLeadOffer::find($waiting_id); */
                            $waiting_lead_offer = WaitingLeadOffer::find($waiting_id);
                            if (!empty($waiting_lead_offer)) {
                                $job = $waiting_lead_offer->job ?? null;
                                if (!empty($job) && $job->job_status == 3) {
                                    $this->waiting_lead_offer = $waiting_lead_offer;
                    
                                    $job->formatted_date = $this->getFormattedDateFromJob($job->id, $waiting_lead_offer->tutor->id);
                    
                                    $this->job = $job;
                                    $this->child = $job->child;
                                    $this->parent = $job->parent;
                                    $this->tutor = $waiting_lead_offer->tutor;
                                    $flag = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
    }

    /**
     * get formatted date from job, tutor
     * @param mixed $job_id
     * @param mixed $tutor_id
     * @return array<mixed|string>[] : [['full_date' => '25/06/2024 6:00PM' , 'date' => 'Sunday 6:00PM'], ...]
     */
    private function getFormattedDateFromJob($job_id, $tutor_id) {
        $job = Job::find($job_id);
        $hour_diff = $this->getTimezoneDiffHours($job->parent_id, $tutor_id);
        $av_str = $job->date ?? '';
        if (!empty($hour_diff)) $av_str = $this->convertTimezone($av_str, $hour_diff, false);
        $av_exp = explode(',', $av_str) ?? [];
        $formatted_date = [];
        foreach ($av_exp as $av) { //ma7
            $item = [];
            $ses_date = $this->generateSessionDate($av, $job->start_date, false);
            $date = $this->getAvailabilitiesFromString1($av)[0];
            $item = [
                'full_date' => explode(' ', $date)[0] . ' ' . $ses_date['date'] . ' at ' . $ses_date['time'],
                'date' => $date
            ];
            $formatted_date[$av] = $item;
        }
        return $formatted_date;
    }

    public function acceptJobFromParent($job_availability)
    {
        try {
            if ($this->waiting_lead_offer->status > 0) throw new \Exception("The waiting list offer already rejected.");
            // if (empty($this->tutor->can_accept_job)) throw new \Exception("The tutor cann't accept the job.");
            if ($this->job->job_status != 3) throw new \Exception("The job was handled.");

            $ses_date = $this->generateSessionDate($job_availability, $this->job->start_date);
            $session_date = $ses_date['date'] ?? '';
            $session_time = $ses_date['time'] ?? '';

            $this->createSessionFromJob($this->job->id, $this->tutor->id, $session_date, $session_time);

            $this->job->formatted_date = $this->getFormattedDateFromJob($this->job->id, $this->tutor->id);
            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $this->job->formatted_date = $this->getFormattedDateFromJob($this->job->id, $this->tutor->id);
            return false;
        }
    }

    public function declineJobFromParent()
    {
        try {
            $body = "Hi " . $this->tutor->first_name . ",<br><br>";
            $body .= "You recently expressed interest in working with the following student:<br><br>";
            $body .= "Name: " . $this->child->child_name . "<br>";
            $body .= "Grade: " . $this->child->child_year . "<br>";
            $body .= "Subject: " . $this->job->subject . "<br>";
            if ($this->job->session_type_id == 1) {
                $body .= "Location: " . $this->job->location . "<br>";
            } else {
                $body .= "Location: Online<br>";
            }
            $body .= "<br>Unfortunately the parent has decided not to go ahead with lessons at this time.<br><br>Don't be disappointed: we have plenty of other opportunities that you would be perfect for in the jobs feed!<br><br>If you have any questions please let us know.<br><br>Team Alchemy";

            $this->sendEmail($this->tutor->tutor_email, 'Re. your recent interest in taking on a student', null, $body);

            $this->waiting_lead_offer->update(['status' => 1]);
            $this->job->formatted_date = $this->getFormattedDateFromJob($this->job->id, $this->tutor->id);
            return true;
        } catch (\Exception $e) {
            $this->job->formatted_date = $this->getFormattedDateFromJob($this->job->id, $this->tutor->id);
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.accept-waiting-list');
    }
}
