<?php

namespace App\Livewire\Admin\EndOfHoliday;

use App\Models\Option;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class NewYearStudentNotScheduled extends Component
{
    public $reminder_email_status_option;

    public function mount()
    {
        $this->reminder_email_status_option = Option::where('option_name', 'new-year-no-lesson-scheduled-cron')->first();
    }

    public function changeReminderEmailStatus()
    {
        try {
            if (!empty($this->reminder_email_status_option)) {
                $this->reminder_email_status_option->update([
                    'option_value' => empty($this->reminder_email_status_option->option_value) ? 1 : 0
                ]);
            } else throw new \Exception('There is no the option');
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The status of no-lesson-scheduled-email-cron is updated succesfully!'
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
        return view('livewire.admin.end-of-holiday.new-year-student-not-scheduled');
    }
}
