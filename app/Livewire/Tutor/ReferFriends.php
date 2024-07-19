<?php

namespace App\Livewire\Tutor;

use App\Trait\Functions;
use App\Trait\WithTutors;
use App\Models\TutorApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class ReferFriends extends Component
{    
    use WithTutors, Functions;
    public $tutor;
    public $referral_amount;
    public $referrals = [];
    public $application_status_arr;
    
    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->referral_amount = $this->getReferralSpecial($tutor->id) ? $this->getOption('referral-special-amount') : $this->getOption('referral-amount');
        $this->referrals = TutorApplication::where('tutor_referral', $tutor->referral_key)->get();
        $this->application_status_arr = $this::APPLICATION_STATUS;
    }

    public function render()
    {
        return view('livewire.tutor.refer-friends');
    }
}
