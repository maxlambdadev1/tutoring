<?php

namespace App\Console\Commands;

use App\Models\JobMatch;
use App\Models\Tutor;
use App\Models\Job;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use Illuminate\Console\Command;

class F2fLessonAutomationAfterMatchingJobs extends Command
{
    use Functions, WithLeads, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:f2f-lesson-automation-after-matching-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "F2f automation based on jobs_match table after automationing jobs according to the distance.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = new \DateTime();
        $jobs = Job::where('job_status', 0)->where('hidden', 0)->where('automation', 1)->where('session_type_id', 1)
            ->whereNot('job_type')->get();
        foreach ($jobs as $job) {
            $job_match = $job->job_match;
            if (!empty($job_match)) {
                $parent = $job->parent;
                $child = $job->child;
                if (empty($job_match->last_updated)) continue;
                $last_updated = \DateTime::createFromFormat('d/m/Y H:i', $job_match->last_updated);
                $timediff = $datetime->getTimestamp() - $last_updated->getTimestamp();
                $tutor_ids = $job_match->tutor_ids_array;
                $ignored_tutors = $this->getIgnoredTutorsForJob($job->id);
                $job_offer = $job->job_offer;
                switch ($job_match->automation_step) {
                    case 0:
                        if ($job_match->reminder1 == 0) {
                            if ($timediff >= 86400) { //first reminder sms to tutors.
                                if (!empty($tutor_ids)) {
                                    foreach ($tutor_ids as $tutor_id) {
                                        $tutor = Tutor::find($tutor_id);
                                        if (!empty($ignored_tutors) && in_array($tutor->id, $ignored_tutors)) continue;
                                        $sms_params = [
                                            'name' => $tutor->tutor_name,
                                            'phone' => $tutor->tutor_phone,
                                        ];
                                        $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                                        $body = "Hey " . $tutor->first_name . ", just a reminder that this student in " . $job->location . " is still available: " . $this->setRedirect($link) . " .";
                                        $this->sendSms($sms_params, $body, null, 1);
                                        $this->addJobAutomationHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Reminder1 SMS sent to " . $tutor->tutor_name,
                                        ]);
                                    }
                                    $job_match->update(['reminder1' => 1]);
                                } else {
                                    if ($timediff < 6 * 86400 && $timediff >= 1 * 86400) {
                                        if (($timediff - 1 * 86400) > 86400 * $job_match->update_avail_status) {
                                            $update_avail_status =  ($timediff - $timediff % 86400) / 86400 - 1;
                                            $job_match->update(['update_avail_status' => $update_avail_status + 1]);
                                            $link = "https://" . env('TUTOR') . "/update-availabilities?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                                            $sms_params = [
                                                'name' => $parent->parent_first_name,
                                                'phone' => $parent->parent_phone,
                                            ];
                                            $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here. \r\nWe are still working on a great tutor for " . $child->first_name . ", but wanted to see if you might have any additional availabilities that could work for lessons?  \r\nPlease let us know here: " . $this->setRedirect($link) . " .";
                                            if ($update_avail_status > 0) {
                                                $sms_body = "Alchemy Tuition reminder: add any additional availabilities to your booking to help us organise your tutor faster: " . $this->setRedirect($link) . " .";
                                            }
                                            $this->sendSms($sms_params, $body);
                                            $this->addJobAutomationHistory([
                                                'job_id' => $job->id,
                                                'comment' => "Update-availabilities(no tutor - 1 day) SMS sent to parent - " . $parent->parent_first_name
                                            ]);
                                        }
                                    }
                                }
                            }
                        } else { //after 2 days, $3 hot job
                            if ($timediff < 3 * 86400 && $timediff >= 2 * 86400 && empty($job_offer) && $job_match->update_avail_status == 0) {
                                $amount1 = '3';
                                $diff = [
                                    'time' => 2 * 86400,
                                    'amount' => $amount1
                                ];
                                $this->upsertJobOffer($job->id, $diff);
                                if (!empty($tutor_ids)) {
                                    foreach ($tutor_ids as $tutor_id) {
                                        $tutor = Tutor::find($tutor_id);
                                        $sms_params = [
                                            'name' => $tutor->tutor_name,
                                            'phone' => $tutor->tutor_phone,
                                        ];
                                        $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                                        $body = "The year " . $child->child_year . " student in " . $job->location . " now comes with a bonus $" . $amount1 . " an hour for life of your lessons! That’s in addition to your 10 lesson increases! Check out the details here: " . $this->setRedirect($link) . " .";
                                        $this->sendSms($sms_params, $body, null, 1);
                                        $this->addJobAutomationHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Hot job $" . $amount1 . " SMS sent to " . $tutor->tutor_name,
                                        ]);
                                    }
                                }
                            }
                            //update-availabilities sms after 3 days.
                            if ($timediff < 8 * 86400 && $timediff >= 3 * 86400) {
                                if (($timediff - 3 * 86400) > 86400 * $job_match->update_avail_status) {
                                    $update_avail_status = ($timediff - $timediff % 86400) / 86400 - 3;
                                    $job_match->update([
                                        'update_avail_status' => $update_avail_status + 1
                                    ]);
                                    if (!empty($job_offer)) {
                                        $job_offer->delete();
                                        $this->addJobHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Removed $3 offer after 3 days."
                                        ]);
                                    }
                                    $link = "https://" . env('TUTOR') . "/update-availabilities?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                                    $sms_params = [
                                        'name' => $parent->parent_first_name,
                                        'phone' => $parent->parent_phone,
                                    ];
                                    $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here. \r\nWe are still working on a great tutor for " . $child->first_name . ", but  wanted to see if you might have any additional availabilities that could work for lessons? \r\nPlease let us know here: " . $this->setRedirect($link) . " .";
                                    $this->sendSms($sms_params, $body);
                                    $this->addJobAutomationHistory([
                                        'job_id' => $job->id,
                                        'comment' => "Update-availabilities SMS sent to parent - " . $parent->parent_name
                                    ]);                                    
                                }
                            }
                        }
                        break;
                    case 1: //second step(after update-avaliabilities)   
                        if ($job_match->reminder2 == 0) { //second reminder sms to tutors 
                            if ($timediff >= 86400) { //after 1 day
                                if (!empty($tutor_ids)) {
                                    foreach ($tutor_ids as $tutor_id) {
                                        $tutor = Tutor::find($tutor_id);
                                        $sms_params = [
                                            'name' => $tutor->tutor_name,
                                            'phone' => $tutor->tutor_phone,
                                        ];
                                        $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                                        $body = "Hi " . $tutor->first_name . ", a reminder that this student in " . $job->location . " is still available: " . $this->setRedirect($link) . " .";
                                        $this->sendSms($sms_params, $body, null, 1);
                                        $this->addJobAutomationHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Reminder2 SMS sent to " . $tutor->tutor_name . " (extra availabilities)",
                                        ]);
                                        $job_match->update(['reminder2' => 1]);
                                    }
                                } else {
                                    if ($timediff < 3 * 86400 && $timediff >= 1 * 86400) {
                                        if (($timediff - 1 * 86400) > 86400 * $job_match->change_online_status) {
                                            $change_online_status = ($timediff - $timediff % 86400)/86400 - 1;  
                                            $job_match->update(['change_online_status' => $change_online_status + 1]);

                                            $link = "https://" . env('TUTOR') . "/booking-update?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                                            $sms_params = [
                                                'name' => $parent->parent_first_name,
                                                'phone' => $parent->parent_phone,
                                            ];
                                            $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here again. \r\nI’ve been working hard to get your child matched up with a tutor but it seems our tutors near you are at capacity - I wanted to see if you’d be open to trying online lessons? We could get this lined up right away. \r\nPlease learn more and let me know here: " . $this->setRedirect($link) . " .";
                                            if ($change_online_status > 0) {
                                                $body = "Alchemy Tuition reminder: have you considered online tutoring? Learn more and let us know here: " . $this->setRedirect($link) . " .";
                                            }
                                            $this->sendSms($sms_params, $body);
                                            $this->addJobAutomationHistory([
                                                'job_id' => $job->id,
                                                'comment' => "'Change to online job' SMS sent to parent - " . $parent->parent_first_name . " due to lack of tutors at step 1." . $link
                                            ]);
                                        }
                                    } 
                                }
                            }
                        } else {
                            //after 2 days
                            if ($timediff < 3 * 86400 && $timediff >= 2 * 86400 && empty($job_offer) && $job_match->change_online_status == 0) {
                                $amount1 = '3';
                                $diff = [
                                    'time' => 5 * 86400,
                                    'amount' => $amount1
                                ];
                                $this->upsertJobOffer($job->id, $diff);
                                if (!empty($tutor_ids)) {
                                    foreach ($tutor_ids as $tutor_id) {
                                        $tutor = Tutor::find($tutor_id);
                                        $sms_params = [
                                            'name' => $tutor->tutor_name,
                                            'phone' => $tutor->tutor_phone,
                                        ];
                                        $link = "https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id);
                                        $body = "The year " . $child->child_year . " student in " . $job->location . " now comes with a bonus $" . $amount1 . " an hour for life of your lessons! That’s in addition to your 10 lesson increases! Check out the details here: " . $this->setRedirect($link) . " .";
                                        $this->sendSms($sms_params, $body, null, 1);
                                        $this->addJobAutomationHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Hot job $" . $amount1 . "(2) SMS sent to " . $tutor->tutor_name,
                                        ]);
                                    }
                                }
                            }
                            if ($timediff < 5 * 86400 && $timediff >= 3 * 86400) {
                                if (($timediff - 3 * 86400) > 86400 * $job_match->change_online_status) {
                                    $change_online_status = ($timediff - $timediff % 86400)/86400 - 3;   
                                    $job_match->update(['change_online_status' => $change_online_status + 1]);
                                    if (!empty($job_offer)) {
                                        $job_offer->delete();
                                        $this->addJobHistory([
                                            'job_id' => $job->id,
                                            'comment' => "Removed $3 offer after 3 days at step 1."
                                        ]);
                                        $link = "https://" . env('TUTOR') . "/booking-update?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                                        $sms_params = [
                                            'name' => $parent->parent_first_name,
                                            'phone' => $parent->parent_phone,
                                        ];
                                        $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here again. \r\nI’ve been working hard to get your child matched up with a tutor but it seems our tutors near you are at capacity - I wanted to see if you’d be open to trying online lessons? We could get this lined up right away. \r\nPlease learn more and let me know here: " . $this->setRedirect($link) . " .";
                                        if ($change_online_status > 0) {
                                            $body = "Alchemy Tuition reminder: have you considered online tutoring? Learn more and let us know here: " . $this->setRedirect($link) . " .";
                                        }
                                        $this->sendSms($sms_params, $body);
                                        $this->addJobAutomationHistory([
                                            'job_id' => $job->id,
                                            'comment' => "'Change to online job' SMS sent to parent - " . $parent->parent_first_name . " due to lack of tutors at step 1." . $link
                                        ]);
                                    }
                                }
                            }
                        }
                        break;
                    case 2:
                        if ($timediff < 3 * 86400 && $timediff > 2 * 86400 && !empty($job_offer) && $job_match->change_online_status == 0) {
                            $job_offer->delete();
                            $this->addJobHistory([
                                'job_id' => $job->id,
                                'comment' => "Removed $5 offer after 2 days."
                            ]);
                            $job_match->increment('change_online_status');

                            $link = "https://" . env('TUTOR') . "/booking-update?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                            $sms_params = [
                                'name' => $parent->parent_first_name,
                                'phone' => $parent->parent_phone,
                            ];
                            $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here again. \r\nI’ve been working hard to get your child matched up with a tutor but it seems our tutors near you are at capacity - I wanted to see if you’d be open to trying online lessons? We could get this lined up right away. \r\nPlease learn more and let me know here: " . $this->setRedirect($link) . " .";
                            $this->sendSms($sms_params, $body);
                            $this->addJobAutomationHistory([
                                'job_id' => $job->id,
                                'comment' => "'Change to online job' SMS sent to parent at step 2 - " . $parent->parent_first_name,
                            ]);
                        }  
                        if ($timediff >= 4 * 86400) {
                            $job_offer->delete();
                            $this->addJobHistory([
                                'job_id' => $job->id,
                                'comment' => "Removed $5 offer after 4 days."
                            ]);
                        }
                        break;
                }
            }
        }
    }
}
