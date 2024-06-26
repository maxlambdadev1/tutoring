<?php

namespace App\Livewire\Admin\EndOfHoliday;

use App\Models\Option;
use App\Models\HolidayReplacement;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Replacement extends Component
{
    use Functions, Mailable;
    public $replacement_status_switch_option;

    public function mount()
    {
        $this->replacement_status_switch_option = Option::where('option_name', 'new-year-replacement-cron')->first();
    }

    public function changeReplacementStatus()
    {
        try {
            if (!empty($this->replacement_status_switch_option)) {
                $this->replacement_status_switch_option->update([
                    'option_value' => empty($this->replacement_status_switch_option->option_value) ? 1 : 0
                ]);
            } else throw new \Exception('There is no the option');
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The status of new-year-replacement-tutor cron is updated succesfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function newYearReplacementSms()
    {
        try {
            $holiday_replacements = HolidayReplacement::where('year', date('Y'))->get();
            if (!empty($holiday_replacements)) {
                foreach ($holiday_replacements as $holiday_replacement) {
                    $replacement_tutor = $holiday_replacement->replacement_tutor;
                    if (!empty($replacement_tutor)) {
                        $child = $replacement_tutor->child;
                        $parent = $replacement_tutor->parent;

                        $link = $this->setRedirect('https://alchemy.team/replacement-tutor?key=' . $replacement_tutor->parent_link);
                        $no_link = $this->setRedirect('https://alchemy.team/thankyou-parent?key=' . base64_encode($holiday_replacement->id));
                        $smsParams = [
                            'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                            'phone' => $parent->parent_phone
                        ];
                        $params = [
                            'studentfirstname' => $child->child_first_name,
                            'link' => $link,
                            'nolink' => $no_link
                        ];
                        $this->sendSms($smsParams, 'new-year-replacement-to-parent-sms', $params);

                        $this->addHolidayReplacementHistory([
                            'holiday_id' => $holiday_replacement->id,
                            'author' =>auth()->user()->admin->admin_name,
                            'comment' => 'Replacement offer SMS sent to parent'
                        ]);
                    }
                }
            }

            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'It is finished successfully!'
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
        return view('livewire.admin.end-of-holiday.replacement');
    }
}
