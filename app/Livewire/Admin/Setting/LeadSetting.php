<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Job;
use App\Models\JobOffer;
use App\Models\Option;
use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class LeadSetting extends Component
{
    use Functions;

    public $offer_amount;
    public $valid_until;
    public $lead_type;

    public $step1_time;
    public $step1_amount;
    public $step2_time;
    public $step2_amount;

    public function mount()
    {
        $hot_lead = [
            'offer' => 5,
            'age' => 0,
            'lead_type' => ''
        ];

        $diff = [
            [
                'time' => 432000,
                'amount' => 3
            ],
            [
                'time' => 684000,
                'amount' => 5
            ],
        ];
        $option = $this->getOption('hot-job-offer') ?? null;
        if (!empty($option)) $hot_lead = unserialize($option);
        $this->offer_amount = $hot_lead['offer'];
        $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
        $this->valid_until = $today->setTimestamp($hot_lead['age'])->format('d/m/Y') ?? '';
        $this->lead_type = $hot_lead['lead_type'] ?? '';

        $option = $this->getOption('job-age-offer') ?? null;
        if (!empty($option)) $diff = unserialize($option);
        $this->step1_time = $diff[0]['time']/3600;
        $this->step1_amount = $diff[0]['amount'];
        $this->step2_time = $diff[1]['time']/3600;
        $this->step2_amount = $diff[1]['amount'];
    }

    public function saveHotLeadSetting()
    {
        try {
            $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
            $date = $today->createFromFormat('d/m/Y', $this->valid_until); 
            $arr = [
                'age' => $date->getTimestamp(),
                'offer' => $this->offer_amount,
                'lead_type' => $this->lead_type
            ];

            Option::updateOrCreate([
                'option_name' => 'hot-job-offer'
            ], [
                'option_value' => serialize($arr)
            ]);

            $author = auth()->user()->admin->admin_name;
            $jobs = Job::where('job_status', 0)->get();
            foreach ($jobs as $job) {
                $comment = 'Added \$' . $this->offer_amount . ' to job offer due to global hot leads';
                if (!empty($job->job_offer)) $comment = 'Updated \$' . $this->offer_amount . ' to job offer due to global hot leads';
                
                JobOffer::updateOrCreate([
                    'job_id' => $job->id
                ], [
                    'offer_amount' => $this->offer_amount,
                    'offer_type' => 'fixed',
                    'expiry' => 'permanent'
                ]);

                $this->addJobHistory([
                    'job_id' => $job->id,
                    'author' => $author,
                    'comment' => $comment
                ]);
            }

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
    
    public function saveLeadAgeSetting()
    { 
        try {
            $arr = [
                [
                'time' => $this->step1_time * 3600,
                'amount' => $this->step1_amount
                ],
                [
                'time' => $this->step2_time * 3600,
                'amount' => $this->step2_amount
                ],
            ];

            Option::updateOrCreate([
                'option_name' => 'job-age-offer'
            ], [
                'option_value' => serialize($arr)
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
        return view('livewire.admin.setting.lead-setting');
    }
}
