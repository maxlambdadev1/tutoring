<?php

namespace App\Livewire\Recruiter;

use App\Trait\Functions;
use App\Trait\WithTutors;
use App\Models\TutorApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('recruiter.layouts.app')]
class ReferFriends extends Component
{    
    use WithTutors, Functions;
    public $recruiter;    
    public $referrals = [];
    public $application_status_arr;
    
    public function mount() {
        $recruiter = auth()->user()->recruiter;
        $this->recruiter = $recruiter;
        $this->referrals = TutorApplication::where('tutor_referral', $recruiter->referral_key)->get();
        $this->application_status_arr = $this::APPLICATION_STATUS;
    }

    public function render()
    {
        return view('livewire.recruiter.refer-friends');
    }
}
