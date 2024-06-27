<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Announcement;
use App\Models\State;
use App\Models\Tutor;
use App\Models\Option;
use App\Models\AlchemyParent;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class General extends Component
{
    use Mailable, Functions;

    public $announcements;
    public $states;

    public $announcement_text;
    public $tutor_sms_announcement_text;
    public $tutor_sms_flag = 0;  //0 or 1
    public $tutor_sms_who = false;  //
    public $tutor_sms_state; //state string
    public $parent_sms_announcement_text;
    public $parent_sms_flag = 0;
    public $parent_sms_who = 1;
    public $parent_sms_state; //state string

    public $job_switch = false;
    public $online_limit;
    public $experience_limit;
    public $daily_target;
    public $cron_time;


    public function mount()
    {
        $this->announcements = Announcement::get();
        $this->states = State::get();
        $this->announcement_text = $this->announcements[0]->an_text;

        $this->tutor_sms_announcement_text = $this->announcements[1]->an_text;
        $this->tutor_sms_flag = $this->announcements[1]->flag ? true : false;
        $this->tutor_sms_who = $this->announcements[1]->who;
        $this->tutor_sms_state = $this->announcements[1]->state;

        $this->parent_sms_announcement_text = $this->announcements[2]->an_text;
        $this->parent_sms_flag = $this->announcements[2]->flag ? true : false;
        $this->parent_sms_who = $this->announcements[2]->who;
        $this->parent_sms_state = $this->announcements[2]->state;

        $this->job_switch = $this->getOption('job-status') ? true : false;
        $this->online_limit = $this->getOption('online-limit') ?? 0;
        $this->experience_limit = $this->getOption('experience-limit') ?? 0;
        $this->daily_target = unserialize($this->getOption('daily-target')) ?? [];
        $this->cron_time = unserialize($this->getOption('cron-time')) ?? [];
    }

    public function saveAnnouncement()
    {
        try {
            if (!empty($this->announcement_text)) {
                $announcement = Announcement::find(1);
                $announcement->update([
                    'an_text' => $this->announcement_text,
                    'an_posted_by' => auth()->user()->id,
                    'an_date' => date('d/m/Y'),
                    'an_time' => date('H:i')
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Settings saved!'
                ]);
            } else throw new \Exception("Please input the announcement.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function saveTutorAnnouncementSms()
    {
        try {
            if (!empty($this->tutor_sms_announcement_text)) {
                $this->tutor_sms_flag = true;
                $announcement = Announcement::find(2);
                $announcement->update([
                    'an_text' => $this->tutor_sms_announcement_text,
                    'an_posted_by' => auth()->user()->id,
                    'state' => $this->tutor_sms_state,
                    'who' => $this->tutor_sms_who,
                    'flag' => $this->tutor_sms_flag,
                    'an_date' => date('d/m/Y'),
                    'an_time' => date('H:i')
                ]);

                $query = Tutor::where('tutor_status', 1);
                if (!empty($this->tutor_sms_state)) $query = $query->where('state', $this->tutor_sms_state);
                if ($this->tutor_sms_who == 2) $query = $query->where('seeking_students', 1);
                if ($this->tutor_sms_who == 3) $query = $query->where('accept_job_status', 1);

                $tutors = $query->get();
                foreach ($tutors as $tutor) {
                    $smsParams = [
                        'name' => $tutor->tutor_name,
                        'phone' => $tutor->tutor_phone
                    ];
                    $this->sendSms($smsParams, $this->tutor_sms_announcement_text);
                    usleep(100000); //100ms
                }
                $this->tutor_sms_flag = false;
                $announcement->update(['flag' => $this->tutor_sms_flag]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Sent SMS to tutors'
                ]);
            } else throw new \Exception("Please input the announcement.");
        } catch (\Exception $e) {
            $this->tutor_sms_flag = false;
            DB::rollBack();
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function saveParentAnnouncementSms()
    {
        try {
            if (!empty($this->parent_sms_announcement_text)) {
                $this->parent_sms_flag = true;
                $announcement = Announcement::find(3);
                $announcement->update([
                    'an_text' => $this->parent_sms_announcement_text,
                    'an_posted_by' => auth()->user()->id,
                    'state' => $this->parent_sms_state,
                    'who' => $this->parent_sms_who,
                    'flag' => $this->parent_sms_flag,
                    'an_date' => date('d/m/Y'),
                    'an_time' => date('H:i')
                ]);

                $query = AlchemyParent::query();
                if (!empty($this->parent_sms_state)) $query = $query->where('parent_state', $this->parent_sms_state);

                if ($this->parent_sms_who == 2) $query = $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('alchemy_children as c')
                        ->whereRaw('c.parent_id = alchemy_parent.id')
                        ->where('c.child_status', '>', 0);
                }); //active parent
                else if ($this->parent_sms_who == 3) $query = $query->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('alchemy_children as c')
                        ->whereRaw('c.parent_id = alchemy_parent.id')
                        ->where('c.child_status', '>', 0);
                }); //inactive parent
                else if ($this->parent_sms_who == 4) $query = $query->where('stripe_customer_id', 'like', '%cus_%')
                    ->where('created_at', '>=', date('Y') . '-01-01')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('alchemy_children as c')
                            ->whereRaw('c.parent_id = alchemy_parent.id')
                            ->where('c.child_status', '>', 0);
                    });

                $parents = $query->get();
                foreach ($parents as $parent) {
                    $smsParams = [
                        'name' => $parent->parent_name,
                        'phone' => $parent->parent_phone
                    ];
                    $this->sendSms($smsParams, $this->parent_sms_announcement_text);
                    usleep(100000); //100ms
                }
                $this->parent_sms_flag = false;
                $announcement->update(['flag' => $this->parent_sms_flag]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Sent SMS to parents'
                ]);
            } else throw new \Exception("Please input the announcement.");
        } catch (\Exception $e) {
            $this->parent_sms_flag = 0;
            DB::rollBack();
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeJobSwitch()
    {
        try {
            $option = Option::where('option_name', 'job-status')->first();
            $option->update([
                'option_value' => $this->job_switch,
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
    
    public function saveOnlineLessonLimit()
    {
        try {
            $option = Option::where('option_name', 'online-limit')->first();
            $option->update([
                'option_value' => $this->online_limit,
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
    
    public function saveExperiencedTutorLimit()
    {
        try {
            $option = Option::where('option_name', 'experience-limit')->first();
            $option->update([
                'option_value' => $this->experience_limit,
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
    
    public function saveDailyTarget()
    {
        try {
            $option = Option::where('option_name', 'daily-target')->first();
            $option->update([
                'option_value' => serialize($this->daily_target),
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
        
    public function saveCronTime()
    {
        try {
            $option = Option::where('option_name', 'cron-time')->first();
            $option->update([
                'option_value' => serialize($this->cron_time),
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
        return view('livewire.admin.setting.general');
    }
}
