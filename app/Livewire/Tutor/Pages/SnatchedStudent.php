<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\JobVisit;
use App\Models\Tutor;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class SnatchedStudent extends Component
{
    use WithLeads;

    public $job;
    public $jobs;
    public $tutor;
    public function mount()
    {
        $tutor_id = null;
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $job_id = explode('=', $exp[0])[1] ?? '';
                    $tutor_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($job_id)) {
                        $job = Job::find($job_id);
                        if ($job->job_status == 0) $this->job = $job;
                        $this->tutor = Tutor::find($tutor_id);
                        if (!empty($this->job) || !empty($this->tutor)) $flag = true;
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $job_id = 4547;
        // $tutor_id = 1062;
        // $this->job = Job::find($job_id);
        // $this->tutor = Tutor::find($tutor_id);
        $jobs = $this->getAllJobs($tutor_id);
        $i = 0;
        $temp_jobs = [];
        foreach ($jobs as $job) {
            if ($job->job_status == 0 && $job->can_accept_job == true) {
                $temp_jobs[] = $job; $i++;
            }
            if ($i >= 5) break;
        }
        $this->jobs = $temp_jobs;
    }

    public function render()
    {
        return view('livewire.tutor.pages.snatched-student');
    }
}
