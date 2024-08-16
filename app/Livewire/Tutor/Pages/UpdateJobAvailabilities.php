<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\AlchemyParent;
use App\Models\Availability;
use App\Models\Tutor;
use App\Models\OnlineAutomationLimit;
use App\Trait\Automationable;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class UpdateJobAvailabilities extends Component
{
    use Functions, Mailable, Automationable, WithLeads;

    public $job;
    public $parent;
    public $child;
    public $availabilities = [];
    public $total_availabilities = [];

    public function mount()
    {
        // $url = request()->query('url') ?? '';
        // $flag = false;
        // if (!empty($url)) {
        //     $details = base64_decode($url);
        //     if (!empty($details)) {
        //         $exp = explode('&', $details);
        //         if (!empty($exp) && count($exp) >= 2) {
        //             $job_id = explode('=', $exp[0])[1] ?? '';
        //             $parent_id = explode('=', $exp[1])[1] ?? '';
        //             if (!empty($job_id) && !empty($parent_id)) {
        //                     $this->job = Job::find($job_id);
        //                     $this->parent = AlchemyParent::find($parent_id);
        //                     if (!empty($this->job) && !empty($this->parent)) $flag = true;
        //             }
        //         }
        //     }
        // }
        // if (!$flag) $this->redirect(env('MAIN_SITE'));
        $this->job = Job::find(4559);
        $this->parent = AlchemyParent::find(2897);
        
        $this->child = $this->job->child ?? null;
        $this->availabilities = $this->job->availabilities ?? [];
        $this->total_availabilities = Availability::get();
    }

    public function updateJobAvailabilities()
    {
        try {
            if (count($this->availabilities) == 0) throw new \Exception('Please select availabilities');
            $av_str = $this->generateBookingAvailability($this->orderAvailabilitiesAccordingToDay($this->availabilities));
            $job = $this->job;
            $job->update(['date' => $av_str]);
            $parent = $this->parent;
            $child = $this->child;
            if ($job->session_type_id == 1) {                
                $job_match = $this->job->job_match ?? null;
                if (!empty($job_match)) {
                    if ($job_match->automation_step > 0) throw new \Exception("You already made your decision");

                    $original_match_tutors = $job_match->tutor_ids_array;
                    $matched_tutors = $this->getAutomationTutors($job->id);
                    $tutor_ids_full = [];
                    if (!empty($matched_tutors)) {
                        foreach ($matched_tutors as $tutor) {
                            if (in_array($tutor->id, $original_match_tutors)) continue;
                            $tutor_ids_full[] = $tutor->id;

                            $sms_body = $this->generateUniqueSms();
                            $smsParams = [
                                'name' => $tutor->tutor_name,
                                'phone' => $tutor->tutor_phone,
                            ];
                            $params = [
                                'subject' => $job->subject,
                                'suburb' => $parent->parent_suburb,
                                'grade' => $child->child_year,
                                'tutorfirstname' => $tutor->first_name,
                                'link' => $this->setRedirect("https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id))
                            ];
                            $this->sendSms($smsParams, $sms_body, $params);

                            if ($job->job_type == 'creative') $comment = "SMS sent to extra tutor - " . $tutor->tutor_name . "(" . $tutor->tutor_phone . " - " . $tutor->distance . " km away) for Creative Kids";
                            else $comment = "SMS sent to extra tutor - " . $tutor->tutor_name . "(" . $tutor->tutor_phone . " - " . $tutor->distance . " km away)";
                            $this->addJobAutomationHistory([
                                'job_id' => $job->id,
                                'comment' => $comment,
                            ]);
                            $this->addJobVolumeOffer($tutor->id, $job->id);
                        }
                    }
                }
            } else {
                OnlineAutomationLimit::where('job_id', $job->id)->update([
                    'update_avail_action_handled' => 1,
                    'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
                ]);
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
    
    public function noUpdateJobAvailabilities()
    {
        try {
            $job = $this->job;
            $parent = $this->parent;
            $child = $this->child;
            if ($job->session_type_id == 1) {                
                $job_match = $this->job->job_match ?? null;
                if (!empty($job_match)) {
                    if ($job_match->automation_step > 0) throw new \Exception("You already made your decision");

					$amount2 = '5';
					$time_num = empty($job_match->tutor_ids) ? 1 : 3;
					$diff = array(
						'time' => $time_num * 86400, //after 1 or 3 days
						'amount' => $amount2
					);
                    $this->upsertJobOffer($job->id, $diff);
                    if (!empty($job_match->tutor_ids_array)) {
                        foreach ($job_match->tutor_ids_array as $tutor_id) {
                            $tutor = Tutor::find($tutor_id);
                            if (!empty($tutor)) {
                                $sms_params = [
                                    'name' => $tutor->tutor_name,
                                    'phone' => $tutor->tutor_phone,
                                ];
                                $link = $this->setRedirect("https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id));
                                $sms_body = "The year " . $child->child_year . " student in " . $job->location . " now comes with a bonus $" . $amount2 . " an hour for the life of your lesson!  That’s in addition to your 10 lesson increases! Check out the details here: " . $link;
                                $this->sendSms($sms_params, $sms_body, null, true);

                                $this->addJobHistory([
                                    'job_id' => $job->id,
                                    'comment' => "Hot job(" . $amount2 . " SMS sent to " . $tutor->tutor_name,
                                ]);
                            }
                        }
                    }
                }
            } else {
                $this->makeJobToWaitingList($job->id);
                $this->addJobHistory([
                    'job_id' => $job->id,
                    'comment' => "The online lead was changed to waiting list because parent don't want to update availabilities"
                ]);
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
        return view('livewire.tutor.pages.update-job-availabilities');
    }
}
