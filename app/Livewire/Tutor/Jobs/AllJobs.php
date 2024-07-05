<?php

namespace App\Livewire\Tutor\Jobs;

use App\Trait\Functions;
use App\Models\Job;
use App\Models\WaitingLeadOffer;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class AllJobs extends Component
{
    use Functions, WithLeads;

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
                    $waiting_leads_offers_count = WaitingLeadOffer::where('status', 0)->where('job_id', $job->id)->get();
                    if ($waiting_leads_offers_count > 0) continue;
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
