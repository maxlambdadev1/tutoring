<?php

namespace App\Livewire\Tutor\Components;

use App\Models\Availability;
use App\Models\Job;
use App\Models\JobReschedule;
use App\Models\WaitingLeadOffer;
use App\Trait\Functions;
use App\Trait\Mailable;
use Livewire\Component;

class RescheduleJobApplyModal extends Component
{
    use Functions, Mailable;

    public $total_availabilities = [];
    public $job;


    public function mount($job_id) {
        $this->job = Job::find($job_id);
        $this->total_availabilities = Availability::get();
    }
    
    public function rescheduleJobApply($job_id, $availabilities) {
        try {
            if (empty($availabilities)) throw new \Exception("Please select the availabilities");

            $job = Job::find($job_id);
            $tutor = auth()->user()->tutor;
            if (!$tutor->have_wwcc) throw new \Exception("You need to have a valid WWCC in order to accept jobs");

            $parent = $job->parent;
            $child = $job->child;

            $waiting = WaitingLeadOffer::where('job_id', $job->id)->where('status', 0)->first();
            if (!empty($waiting))  throw new \Exception("This one has already been applied! Check out the jobs board for other students near you");
            
            $check_waiting = WaitingLeadOffer::where('job_id', $job->id)->where('tutor_id', $tutor->id)->first();
            if (!empty($check_waiting))  throw new \Exception("You've already applied before! You've already applied for this job opportunity before. We only send them your details once, so you won't be able to apply again. Please try another opportunity near you!");


            $av_str = $this->generateBookingAvailability($availabilities);

            $waiting = WaitingLeadOffer::create([
                'job_id' => $job->id,
                'tutor_id' => $tutor->id,
                'date' => $av_str,
                'status' => 0,
            ]);
            $this->addJobHistory([
                'job_id' => $job->id,
                'author' => $tutor->tutor_name,
                'comment' => "The tutor " . $tutor->tutor_name . "(" . $tutor->tutor_email . ") was applied to this job."
            ]);

            $secret = sha1($waiting->id . env('SHARED_SECRET'));
            $params = [
                'email' => $parent->parent_email,
                'parentfirstname' => $parent->parent_first_name,
                'studentfirstname' => $child->first_name,
                'yeslink' => "https://" . env('TUTOR') . "/accept-waiting-list?url=" . base64_encode("secret=" . $secret . "&waiting_id=" . $waiting->id),
                'nolink' => "https://" . env('TUTOR') . "/reject-waiting-list?url=" . base64_encode("secret=" . $secret . "&waiting_id=" . $waiting->id),
            ];
            $this->sendEmail($params['email'], 'waiting-list-parent-offer-email', $params);

            $sms_params = [
                'phone' => $parent->parent_phone,
                'name' => $parent->parent_name,
            ];
            $sms_body = "Hi " . $parent->parent_first_name . ", we have had a great tutor for " . $child->first_name . " become available! Please check your email for details or to opt out - Team Alchemy";
            $this->sendSms($sms_params, $sms_body);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'title' => 'Your application has been submitted!',
                'message' => 'We will reach out to the parent and let you know the outcome within 2 business days.'
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
        return view('livewire.tutor.components.reschedule-job-apply-modal');
    }
}
