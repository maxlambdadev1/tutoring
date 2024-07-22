<?php

namespace App\Livewire\Recruiter;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('recruiter.layouts.app')]
class ReferralPack extends Component
{
    public function render()
    {
        return view('livewire.recruiter.referral-pack');
    }
}
