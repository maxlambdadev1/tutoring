<?php

namespace App\Livewire\Tutor\Components;

use App\Models\Availability;
use App\Models\Job;
use App\Models\JobReschedule;
use App\Trait\Functions;
use Livewire\Component;

class RescheduleFirstSessionModal extends Component
{
    use Functions;

    public $total_availabilities = [];
    public $job;


    public function mount($job_id) {
        $this->job = Job::find($job_id);
        $this->total_availabilities = Availability::get();
    }
    
    public function rescheduleJob($job_id, $availabilities, $make_av) {
        try {
            if (empty($availabilities)) throw new \Exception("Please select the availabilities");

            $job = Job::find($job_id);
            $tutor = auth()->user()->tutor;

            $av_str = $this->generateBookingAvailability($availabilities);
            if (!!$make_av) $tutor->update([
                'availabilities' => $av_str
            ]);

            $reschedule_date_str = implode(',', $this->getAvailabilitiesFromString1($av_str));
            //send email to Nic

            JobReschedule::create([
                'job_id' => $job->id,
                'tutor_id' => $tutor->id,
                'date' => $reschedule_date_str,
                'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i')
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Thank you! If they can do this new time we will let you know.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tutor.components.reschedule-first-session-modal');
    }
}
