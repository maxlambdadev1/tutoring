<?php

namespace App\Livewire\Tutor\Jobs;

use App\Trait\Functions;
use App\Models\Job;
use App\Models\RejectedJob;
use App\Models\WaitingLeadOffer;
use App\Trait\PriceCalculatable;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class AllJobs extends Component
{
    use Functions, WithLeads, PriceCalculatable;

    public $type = 'instance-f2f';
    public $jobs = [];
    public $tutor;
    public $under_18 = false;
    public $online_limit;
    public $experienced_limit;

    public function mount()
    {
        $this->online_limit = $this->getOption('online-limit');
        $this->experienced_limit = $this->getOption('experience-limit') ?? 50;
        $tutor = auth()->user()->tutor;

        $today = new \DateTime('now');
        $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
        $interval = $today->diff($birth);
        if ($interval->y < 18) $this->under_18 = true;

        $this->tutor = $tutor;

        $this->getJobs();
    }

    public function changeType($type)
    {
        $this->type = $type;
        $this->getJobs();
    }

    public function getJobs()
    {
        $tutor = $this->tutor;
        $tutor_lat = $tutor->lat ?? 0;
        $tutor_lon = $tutor->lon ?? 0;

        $job_status_value = $this->getOption('job-status') ?? false;
        if (!$job_status_value) {
            $this->jobs = [];
            return;
        }

        $query = Job::where('hidden', 0);
        if (!$tutor->online_acceptable_status) $query = $query->whereNot('session_type_id', 2);
        if ($this->under_18) $query = $query->whereNot('session_type_id', 1);

        if ($this->type == 'instance-f2f') $query = $query->where('job_status', 0)->where('session_type_id', 1);
        else if ($this->type == 'instance-online') $query = $query->where('job_status', 0)->where('session_type_id', 2);
        else if ($this->type = 'waiting-list') $query = $query->where('job_status', 3);

        $temp_jobs = $query->get();
        $jobs = [];
        if (!empty($temp_jobs)) {
            foreach ($temp_jobs as $job) {
                $ignored_tutors = $this->getIgnoredTutorsForJob($job->id);
                if (!empty($ignored_tutors) && in_array($tutor->id, $ignored_tutors)) continue;
                
                if (!!$job->vaccinated && $job->session_type_id == 1 && !$tutor->vaccinated) continue;

                if (empty($job->date)) continue;

                if ($job->job_status == 3) {
                    $waiting_leads_offers_count = WaitingLeadOffer::where('status', 0)->where('job_id', $job->id)->count();
                    if ($waiting_leads_offers_count > 0) continue;
                }

                $av_exp = explode(',', $job->date);
                $formatted_date = [];
                foreach ($av_exp as $av) {
                    $av_booking = $this->getAvailabilitiesFromString1($av)[0];
                    $ses_date = $this->generateSessionDate($av, $job->start_date);
                    $formatted_date = [
                        'date' => $av_booking,
                        'av' => $av,
                        'full_date' => explode(' ', $av_booking)[0] . ' ' . $ses_date['date'] . ' at ' . $ses_date['time']
                    ];
                    $job->formatted_date = $formatted_date;
                }
                $job->create_time = \DateTime::createFromFormat('d/m/Y H:i', $job->create_time)->format('Y-m-d H:i');

                $child = $job->child;
                if (!empty($child)) {
                    $parent = $child->parent;
                    if ($parent->parent_state != $tutor->state) continue;

                    $child_lat = $parent->parent_lat ?? 0;
                    $child_lon = $parent->parent_lon ?? 0;
                    $distance = $this->calcDistance($child_lat, $child_lon, $tutor_lat, $tutor_lon);
                    $job->distance = $distance;

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

                    $rejected_jobs = RejectedJob::where('tutor_id', $tutor->id)->first();
                    if (!empty($rejected_jobs)) {
                        $rejected_exp = explode(',', $rejected_jobs->job_ids);
                        if (in_array($job->id, $rejected_exp)) continue;
                    }
                }


                $jobs[] = $job;
            }
        }

        $this->jobs = $jobs;
    }

    public function render()
    {
        return view('livewire.tutor.jobs.all-jobs');
    }
}
