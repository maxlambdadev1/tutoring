<?php

namespace App\Livewire\Tutor\Jobs;

use App\Models\Job;
use App\Models\Session;
use App\Models\ReplacementTutor;
use Carbon\Carbon;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class Detail extends Component
{
    use Functions, WithLeads, PriceCalculatable, Mailable, Sessionable;


    public $job;
    public $tutor;
    public $under_18 = false;
    public $online_limit;
    public $experienced_limit;
    public $job_availability;
    public $special_request_response;

    public function mount($job_id)
    {
        $this->init($job_id);
    }

    private function init($job_id)
    {
        $job = Job::find($job_id);
        $this->online_limit = $this->getOption('online-limit');
        $this->experienced_limit = $this->getOption('experience-limit') ?? 50;
        $tutor = auth()->user()->tutor;

        $today = new \DateTime('now');
        $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
        $interval = $today->diff($birth);
        if ($interval->y < 18) $this->under_18 = true;

        if (!empty($job)) {
            $job_offer = $job->job_offer;
            if (!empty($job_offer)) {
                $datetime = new \DateTime('Australia/Sydney');
                $job->job_type  = 'hot';
                if ($job_offer->expiry == 'permanent') {
                    if ($job_offer->offer_type == 'fixed') $job->job_offer_price = $this->getCoreTutorPrice($job->session_type_id) + $job_offer->offer_amount;
                } elseif ($datetime->getTimestamp() <= $job_offer->expiry) {
                    if ($job_offer->offer_type == 'fixed')  $job->job_offer_price = $this->getCoreTutorPrice($job->session_type_id) + $job_offer->offer_amount;
                }
            }

            $hour_diff = $this->getTimezoneDiffHours($job->parent_id, $tutor->id);
            $av_str = $job->date ?? '';
            if (!empty($hour_diff)) $av_str = $this->convertTimezone($av_str, $hour_diff);
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
            $job->formatted_date = $formatted_date;

            $job->coords = [
                'tutor_lat' => $tutor->lat ?? 0,
                'tutor_lon' => $tutor->lon ?? 0,
                'child_lat' => $job->parent->parent_lat ?? 0,
                'child_lon' => $job->parent->parent_lon ?? 0
            ];
        }

        $this->job = $job;
        $this->tutor = $tutor;
    }

    public function acceptJob()
    {
        try {
            $job = $this->job;
            $this->init($job->id);

            $tutor = $this->tutor;

            if ($job->job_status !== 0) throw new \Exception("The job does not exist.");
            if ($tutor->accept_job_status < 1) throw new \Exception("You are blocked from accepting the job. Please contact to supporter.");
            if (!!$this->under_18 && $job->session_type_id == 1) throw new \Exception("You can't accept all f2f jobs.");
            if (!empty($job->prefered_gender) && $tutor->gender != $job->prefered_gender) throw new \Exception("This job has a prefered gender.");
            if (!!$job->vaccinated && !$tutor->vaccinated && $job->session_type_id == 1) throw new \Exception("This job is for only experienced tutors.");

            $accepted_jobs = Job::where('accepted_by', $tutor->id)
                ->whereRaw("STR_TO_DATE(accepted_on, '%d/%m/%Y')=CURDATE()")->count();
            if ($accepted_jobs >= 3) throw new \Exception("You have accepted the maximum student opportunities for one day - please try again tomorrow!");

            $parent = $job->parent;
            $child = $job->child;
            if (!empty($job->special_request_content)) {
                if (empty($this->special_request_response)) throw new \Exception("You have to write your response to parent's request.");

                $job->update([
                    'job_status' => 4,
                    'accepted_by' => $tutor->id,
                    'last_updated' => date('d/m/Y H:i'),
                    'converted_by' => 'tutor',
                    'special_request_response' => $this->special_request_response,
                    'tutor_suggested_session_date' => $this->job_availability
                ]);
                $this->addTutorHistory([
                    'tutor_id' => $tutor->id,
                    'author' => $tutor->tutor_name,
                    'comment' => "Tutor accepted a new student(special request job): " . $child->child_name
                ]);

                $params = [
                    'tutorfirstname' => $tutor->first_name ?? '',
                    'subject' => $job->subject,
                    'grade' => $child->child_year,
                    'email' => $tutor->tutor_email,
                    'sessiontype' => $job->session_type_id == 1 ? 'Face to Face' : 'Online',
                    'specialrequirement' => $job->special_request_content
                ];
                $this->sendEmail($tutor->tutor_email, 'special-requirement-tutor-submission-email', $params);

                //send email to supporter
            } else {
                $ses_date = $this->generateSessionDate($this->job_availability, $this->job->start_date);
                $session_date = $ses_date['date'] ?? '';
                $session_time = $ses_date['time'] ?? '';

                $this->createSessionFromJob($job->id, $tutor->id, $session_date, $session_time);
            }

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
        return view('livewire.tutor.jobs.detail');
    }
}
