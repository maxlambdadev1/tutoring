<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\Tutor;
use App\Models\AlchemyParent;
use App\Trait\Automationable;
use App\Trait\Functions;
use App\Trait\Mailable;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class BookingUpdate extends Component
{
    use Functions, Mailable, Automationable;

    public $job;
    public $parent;

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $job_id = explode('=', $exp[0])[1] ?? '';
                    $parent_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($job_id) && !empty($parent_id)) {
                        $job = Job::find($job_id);
                        $parent = AlchemyParent::find($parent_id);
                        if (!empty($job) && !empty($parent) && $job->session_type_id == 1 && $job->job_status == 0) {
                            $this->job = $job;
                            $this->parent = $parent;
                            $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));

        // $this->job = Job::find(4559);
        // $this->parent = AlchemyParent::find(2897);
    }

    public function changeJobToOnline()
    {
        try {
            $job = $this->job;
            if ($job->session_type_id == 2 || $job->job_status != 0) throw new \Exception("You cann't make any change.");

            $job->update([
                'session_type_id' => 2,
                'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $this->addJobHistory([
                'job_id' => $job->id,
                'comment' => "The lead was changed to online from the parent."
            ]);

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function noChangeJobToOnline()
    {
        try {
            $job = $this->job;
            $job_match = $job->job_match;
            if (!empty($job_match)) {
                if ($job_match->automation_step == 1) {
                    $this->makeJobToWaitingList($job->id);
                    $this->addJobHistory([
                        'job_id' => $job->id,
                        'comment' => "The lead was changed to waiting list from the parent."
                    ]);
                } else if ($job_match->automation_step == 2) {
                    $parent = $job->parent;
                    $child = $job->child;
                    $tutor_ids = $job_match->tutor_ids_array;
                    $amount2 = "5";
                    $time_num = empty($tutor_ids) ? 3 : 5;
                    $diff = [
                        'time' => $time_num * 86400,
                        'amount' => $amount2,
                    ];
                    $this->upsertJobOffer($job->id, $diff);
                    if (!empty($tutor_ids)) {
                        foreach ($tutor_ids as $tutor_id) {
                            $tutor = Tutor::find($tutor_id);
                            $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                            $sms_params = [
                                'name' => $tutor->tutor_name,
                                'phone' => $tutor->tutor_phone,
                            ];
                            $body = "The year " . $child->child_year . " student in " . $job->location . " now comes with a bonus $" . $amount2 . " an hour for the life of your lessons!  That’s in addition to your 10 lesson increases! Check out the details here: " . $this->setRedirect($link) . " .";
                            $this->sendSms($sms_params, $body, null, 1);

                            $this->addJobHistory([
                                'job_id' => $job->id,
                                'comment' => "Hot job(" . $amount2 . ")-2 SMS sent to " . $tutor->tutor_name,
                            ]);
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.booking-update');
    }
}
