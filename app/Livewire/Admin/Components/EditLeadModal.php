<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Job;
use App\Models\Availability;
use App\Models\JobMatch;
use App\Models\User;
use App\Models\State;
use App\Models\SessionType;
use App\Models\Grade;
use App\Models\Subject;
use App\Trait\WithLeads;

class EditLeadModal extends Component
{
    use WithLeads;

    public $job;
    public $start_date;
    public $session_type_id;
    public $state_id;
    public $subject;
    public $suburb;
    public $prefered_gender;
    public $job_notes;
    public $offer_type;
    public $offer_amount;
    public $offer_valid;
    public $discount_type;
    public $discount_amount;
    public $experienced_tutor;
    public $tutor_apply_offer;
    public $parent_apply_discount;


    public function mount($job_id) {
        $this->job = Job::find($job_id);
        $this->start_date = $this->job->start_date;
        $this->session_type_id = $this->job->session_type_id;
        $this->state_id = State::where('name', $this->job->parent->parent_state)->first()->id ?? 0;
        $this->subject = $this->job->subject;
        $this->suburb = $this->job->location;
        $this->prefered_gender = $this->job->prefered_gender;
        $this->job_notes = $this->job->job_notes;
        if (!empty($this->job->job_offer)) {
            $job_offer = $this->job->job_offer;
            $this->offer_type = $job_offer->offer_type;
            $this->offer_amount = $job_offer->offer_amount;
            $this->offer_valid = $job_offer->expiry;
        }
        if (!empty($this->job->parent->price_parent_discount)) {
            $parent_discount = $this->job->parent->price_parent_discount;
            $this->discount_type = $parent_discount->discount_type;
            $this->discount_amount = $parent_discount->discount_amount;
        }
        $this->experienced_tutor = !!$this->job->experienced_tutor;
        $this->tutor_apply_offer = !empty($this->job->job_offer) ? true : false;
        $this->parent_apply_discount = !empty($this->job->parent->price_parent_discount) ? true : false;
    }
    public function render()
    {
        $job = $this->job;
        $states = State::get();
        $session_types = SessionType::where('kind', 'normal')->get();
        $sources = $this::LEAD_SOURCE;
        $offer_valid_list = $this::VALID_UNTIL;
        $grades = Grade::get();
        $subjects = Subject::get();
        $total_availabilities = Availability::get();
        return view('livewire.admin.components.edit-lead-modal', compact('job', 'states', 'session_types', 'sources', 'offer_valid_list', 'grades', 'total_availabilities', 'subjects'));
    }
    
    public function editLead1($job_id, $start_date)
    {
        try {
            $job = Job::find($job_id);
            $this->start_date = $start_date;
            if ($job->job_type == 'creative') {
                $job->update([
                    'start_date' => !empty($this->start_date) ? $this->start_date : 'ASAP',
                    'location' => $this->suburb,
                    'job_notes' => $this->job_notes,
                    'session_type_id' => $this->session_type_id,
                ]);
            } else {
                $job->update([
                    'start_date' => $this->start_date,
                    'subject' => $this->subject,
                    'location' => $this->suburb,
                    'prefered_gender' => $this->prefered_gender,
                    'job_notes' => $this->job_notes,
                    'session_type_id' => $this->session_type_id,
                    'experienced_tutor' => $this->experienced_tutor
                ]);
            }

            if (!$this->tutor_apply_offer) {
                $this->offer_type = "";
                $this->offer_amount = "";
            }
            $this->saveJobOffer($job->id, $this->offer_type, $this->offer_amount, $this->offer_valid);
            
            if (!$this->parent_apply_discount) {
                $this->discount_type = "";
                $this->discount_amount = "";
            }
            $this->saveParentDiscount($job->parent->id, $this->discount_type, $this->discount_amount);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly edited the lead'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateAvailabilities($job_id, $availabilities) {
        try {
            $job = Job::find($job_id);
            $job->update([
                'date' => $this->generateBookingAvailability($availabilities)
            ]);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly updated the availabilities'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function stopAutomation($job_id) {
        try {
            $job_match = JobMatch::where('job_id', $job_id)->first();
            if (!empty($job_match)) $job_match->delete();

            $this->addJobHistory([
                'job_id' => $job_id,
                'author' => User::find(auth()->user()->id)->admin->admin_name,
                'comment' => 'Stopped the automation for editing the lead.'
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly stopped the automation'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
