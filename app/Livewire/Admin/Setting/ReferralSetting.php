<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Option;
use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ReferralSetting extends Component
{
    use Functions;

    public $referral_amount;
    public $referral_special_amount;
    public $referral_recruiter_amount;

    public function mount()
    {
        $this->referral_amount = $this->getOption('referral-amount') ?? '';
        $this->referral_special_amount = $this->getOption('referral-special-amount') ?? '';
        $this->referral_recruiter_amount = $this->getOption('referral-recruiter-amount') ?? '';
    }

    public function saveReferralSetting()
    {
        try {
            Option::updateOrCreate([
                'option_name' => 'referral-amount'
            ], [
                'option_value' => $this->referral_amount,
            ]);

            Option::updateOrCreate([
                'option_name' => 'referral-special-amount'
            ], [
                'option_value' => $this->referral_special_amount,
            ]);

            Option::updateOrCreate([
                'option_name' => 'referral-recruiter-amount'
            ], [
                'option_value' => $this->referral_recruiter_amount,
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Settings saved!'
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
        return view('livewire.admin.setting.referral-setting');
    }
}
