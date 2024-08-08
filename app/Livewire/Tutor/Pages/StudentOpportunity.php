<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\JobVisit;
use App\Models\Tutor;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class StudentOpportunity extends Component
{
    public $job;
    public $tutor;
    public $coords;

    public function mount()
    {
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

        $job_visit = JobVisit::where('job_id', $this->job->id)->where('tutor_id', $this->tutor->id)->first();
        if (!empty($job_visit)) {
            $job_visit->increment('cnt');
        } else {
            JobVisit::create([
                'job_id' => $this->job->id,
                'tutor_id' => $this->tutor->id,
                'cnt' => 1
            ]);
        }

        $this->coords = [
            'lat' => $this->job->parent->parent_lat ?? 0,
            'lon' => $this->job->parent->parent_lon ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.tutor.pages.student-opportunity');
    }
}
