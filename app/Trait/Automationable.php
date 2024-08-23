<?php

namespace App\Trait;

use App\Models\Option;
use App\Models\Job;
use App\Models\JobMatch;
use App\Models\OnlineAutomationLimit;
use App\Models\User;
use App\Models\Tutor;
use Illuminate\Support\Facades\DB;
use App\Trait\Mailable;
use App\Trait\Functions;

trait Automationable
{
    use Mailable, Functions, WithLeads;

    /**
     * Automation for the job.
     * @param int $job_id
     * @return void
     */
    public function findTutorForJob($job_id)
    {
        $job = Job::find($job_id);
        if (!empty($job)) {
            if ($job->session_type_id == 1) $this->findTutorForF2F($job_id);
            else $this->findTutorForOnline($job_id);
        }
    }

    /**
     * Automation for f2f job
     * @param int $job_id
     * @return void
     */
    public function findTutorForF2F($job_id) {
        $distances = unserialize($this->getOption('job-min-distance')) ?? [];
        $max_distance = max($distances) ?? 0;
        $job = Job::where('job_status', 0)->where('hidden', 0)->where('automation', 1)
            ->where('session_type_id', 1)->where('id', $job_id)->first();
        if (!empty($job)) {
            $parent = $job->parent;
            $child = $job->child;
            $child_lat = $parent->parent_lat ?? 0;
            $child_lon = $parent->parent_lon ?? 0;
            if (!empty($parent) && !empty($child)) {
                $tutors = [];
                $tutor_query = $this->AutomationTutorsQuery($job_id);
                $temp_tutors = $tutor_query->get();
                if (!empty($temp_tutors)) {
                    foreach ($temp_tutors as $tutor) {
                        $check = $this->checkRejectedAndAvailabilities($job, $tutor, $parent);
                        if (!$check) continue;

                        $tutor_lat = $tutor->lat ?? 0;
                        $tutor_lon = $tutor->lon ?? 0;
                        $distance = $this->calcDistance($child_lat, $child_lon, $tutor_lat, $tutor_lon);
                        $tutor->distance = $distance;
                        if ($distance > $max_distance) continue;
                        $tutors[] = $tutor;
                    }

                    if (!empty($tutors)) {
                        uasort($tutors, function ($a, $b) {
                            return ($a->distance >= $b->distance) ? 1 : -1;
                        });
                    }
                }
                $job_match = $job->job_match;
                $tutor_ids = [];
                if (!empty($job_match)) {
                    if (in_array($job_match->distance, $distances)) {
                        if ($job_match->distance != end($distances)) $distances = array_splice($distances, array_search($job_match->distance, $distances) + 1);
                        else return;
                    }
                    $tutor_ids = $job_match->tutor_ids_array;
                }
                foreach ($distances as $min_dist) {
                    $suitable = false;
                    foreach ($tutors as $tutor) {
                        if ($tutor->distance > $min_dist || $tutor->distance < $min_dist - 1) continue;
                        if (in_array($tutor->id, $tutor_ids)) continue;
                        
                        $check = $this->checkRejectedAndAvailabilities($job, $tutor, $parent);
                        if (!$check) continue;

                        $suitable = true;
                        $tutor_ids[] = $tutor->id;

                        $sms_params = [
                            'name' => $tutor->tutor_name,
                            'phone' => $tutor->tutor_phone,
                        ];
                        if ($job->job_type == 'creative') {
                            $body = "Hi %%tutorfirstname%%! We have a Creative Writing Workshop opportunity near you. The student is in year %%grade%% and lives in %%suburb%%. This is a one-off 1.5 hour lesson. Learn more here: %%link%%";
                            $comment = "SMS sent to " . $tutor->tutor_name . "(" . $tutor->tutor_phone . " - " . $tutor->distance . " km away)";
                        } else {
                            $body = $this->generateUniqueSms();
                            if (!empty($job->job_offer)) {
                                $comment = "SMS sent for hot lead to " . $tutor->tutor_name . "(" . $tutor->tutor_phone . " - " . $tutor->distance . " km away)";
                            } else $comment = "SMS sent to " . $tutor->tutor_name . "(" . $tutor->tutor_phone . " - " . $tutor->distance . " km away)";
                        }
                        
                        $params = [
                            'subject' => $job->subject,
                            'suburb' => $parent->parent_suburb,
                            'grade' => $child->child_year,
                            'tutorfirstname' => $tutor->first_name,
                            'link' => $this->setRedirect("https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id))
                        ];
                        $this->sendSms($sms_params, $body, $params);
                        $this->addJobAutomationHistory([
                            'job_id' => $job->id,
                            'comment' => $comment,
                        ]);
                        $this->addJobVolumeOffer($tutor->id, $job->id);
                    }
                    if ($suitable) {
                        if (empty($job_match)) {
                            $job_match = JobMatch::create([
                                'job_id' => $job->id,
                                'distance' => $min_dist,
                                'tutor_ids' => implode(';', $tutor_ids),
                                'last_updated' =>  (new \DateTime('now'))->format('d/m/Y H:i'),
                            ]);
                        } else $job_match->update([
                            'distance' => $min_dist,
                            'tutor_ids' => implode(';', $tutor_ids),
                            'last_updated' =>  (new \DateTime('now'))->format('d/m/Y H:i'),
                        ]);

                        break;
                    }
                }
            }
        }
    }

    /**
     * Automation for online job
     * @param int $job_id
     * @return void
     */
    public function findTutorForOnline($job_id)
    {
        $crons = $this->getOption("cron-time");
        $cron_arr = unserialize($crons) ?? ['number_tutors_online' => 100];
        $number_tutors = $cron_arr['number_tutors_online'] ?? 1;
        $datetime = new \DateTime();

        $job = Job::find($job_id);
        if (!empty($job) && $job->automation == 1 && $job->hidden == 0 && $job->session_type_id == 2) {
            $check_number_tutors = 0;
            $parent = $job->parent;
            $child = $job->child;
            $online_limit = $job->online_automation_limit;
            $tutor_ids = [];

            $timediff = 0;
            if (empty($online_limit)) $online_limit = OnlineAutomationLimit::create([
                'tutor_ids' => '',
                'job_id' => $job->id,
                'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);
            else {
                $last_update = \DateTime::createFromFormat('d/m/Y H:i', $online_limit->last_updated);
                $timediff = $datetime->getTimestamp() - $last_update->getTimestamp();
                $tutor_ids = $online_limit->tutor_ids_array;
            }

            $tutor_query = $this->AutomationTutorsQuery($job_id);
            if ($timediff < 3 * 86400) {
                if ($timediff < 86400)  $tutor_query = $tutor_query->where('seeking_students', 1)
                    ->orderBy('non_metro', 'DESC')->orderBy('online_automation_timestamp', 'ASC')->orderBy('seeking_students_timestamp', 'DESC');
                else if ($timediff < 2 * 86400)  $tutor_query = $tutor_query->whereRaw("TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 720")
                    ->orderBy('non_metro', 'DESC')->orderBy('seeking_students', 'DESC')->orderBy('created_at', 'DESC')->orderBy('seeking_students_timestamp', 'DESC');
                else $tutor_query = $tutor_query->orderBy('non_metro', 'DESC')->orderBy('seeking_students', 'DESC')->orderBy('online_automation_timestamp', 'ASC')->orderBy('seeking_students_timestamp', 'DESC');
                
                $temp_tutors = $tutor_query->get(); 
                if (!empty($temp_tutors)) {
                    foreach ($temp_tutors as $tutor) {
                        if (in_array($tutor->id, $tutor_ids)) continue;
                        $check = $this->checkRejectedAndAvailabilities($job, $tutor, $parent);
                        if (!$check) continue;

                        $tutor_ids[] = $tutor->id;
                        $online_limit->update([
                            'tutor_ids' => implode(';', $tutor_ids),
                        ]);

                        $this->addJobVolumeOffer($tutor->id, $job->id);
                        $link = $this->setRedirect("https://" . env('TUTOR') . "/student-opportunity?url=" . base64_encode("job_id=" . $job->id . "&tutor_id=" . $tutor->id));
                        if ($job->job_type == 'creative') {
                            $body = "Hi " . $tutor->first_name . "! We have an online Creative Writing Workshop opportunity that we think you'd be great for. The student is in year " . $child->child_year ." and this workshop would be ONLINE. This is a one-off 1.5 hour lesson. Learn more here: " . $link;
                            $comment = "SMS sent to " . $tutor->tutor_name . "(" . $tutor->tutor_phone . ") for creative kids.";
                        } else {
                            $body = "New ONLINE opportunity: Year " . $child->child_year . " student looking for help with " . $job->subject . ". Check it out here: " . $link;
                            $comment = "SMS sent to " . $tutor->tutor_name . "(" . $tutor->tutor_phone . ") for online lesson";
                        }
                        $sms_params = [
                            'name' => $tutor->tutor_name,
                            'phone' => $tutor->tutor_phone,
                        ];
                        $this->sendSms($sms_params, $body, null, 1);
                        $tutor->update(['online_automation_timestamp' => time()]);
                        $this->addJobAutomationHistory([
                            'job_id' => $job->id,
                            'comment' => $comment,
                        ]);
                        
                        $check_number_tutors++;
                        if ($check_number_tutors >= $number_tutors) break;
                    }
                }
            } else {
                $job_match = $job->job_match;
                if (!empty($job_match) || !empty($online_limit) && $online_limit->update_avail_action_handled > 0) {

                } else {
                    if ($timediff - 3 * 86400 > 86400 * $online_limit->update_avail_status) {
                        $online_limit->increment('update_avail_status');
                        $link = "https://" . env('TUTOR') . "/update-availabilities?url=" . base64_encode("job_id=" . $job->id . "&parent_id=" . $parent->id);
                        $body = "Hi " . $parent->parent_first_name . ", Nadine from Alchemy Tuition here.  \r\nWe are still working on a great tutor for " . $child->first_name . ", but wanted to see if you might have any additional availabilities that could work for lessons?  \r\nPlease let us know here: " . $this->setRedirect($link);
                        if ($online_limit->update_avail_status > 0) $body = "Alchemy Tuition reminder: add any additional availabilities to your booking to help us organise your tutor faster: " . $this->setRedirect($link);
                        $sms_params = [
                            'name' => $parent->parent_first_name,
                            'phone' => $parent->parent_phone,
                        ];
                        $this->sendSms($sms_params, $body);

                        $this->addJobAutomationHistory([
                            'job_id' => $job->id,
                            'comment' => "Update-availabilities SMS sent to parent - " . $parent->parent_first_name . "(" . $link . ")"
                        ]);
                    }
                }
            }
        }
    }

    public function sendOnlineTutoringEmail($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = auth()->user()->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $params = [];
                $params['username'] = $admin->admin_name;
                $params['userfirstname'] = $admin_name_arr[0];
                $params['useremail'] = $admin->user->email;
                $params['studentfirstname'] = $child_name_arr[0];
                $params['studentname'] = $child_name_arr[0];
                $params['parentfirstname'] = $parent->parent_first_name;
                $params['email'] = $parent->parent_email;
                $this->sendEmail($parent->parent_email, 'online-tutoring-offer-email', $params); //check

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'Online option email sent to parent',
                    'author' => $admin->admin_name
                ]);

                return true;
            } else {
                throw new \Exception('The job is not existed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendWelcomeEmailAndSms($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = auth()->user()->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $params = [];
                $params['username'] = $admin->admin_name;
                $params['userfirstname'] = $admin_name_arr[0];
                $params['useremail'] = $admin->user->email;
                $params['studentname'] = $child_name_arr[0];
                $params['parentfirstname'] = $parent->parent_first_name;
                $params['email'] = $parent->parent_email;
                $this->sendEmail($parent->parent_email, 'parent-welcome-call-email', $params);

                $params = array(
                    'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                    'phone' => $parent->parent_phone,
                    'body' => "Hi " . $parent->parent_first_name . ", " . $admin_name_arr[0] . " from Alchemy Tuition here.\nJust letting you know I've received your tutor request for " . $child_name_arr[0] . " and am working on matching them up with one of our amazing tutors.\nIf there are any details you'd like to add or discuss further please call me on 1300 914 329.\nAs soon as I have everything organised I will confirm the first lesson with you by SMS and email!\n" . $admin_name_arr[0] . ", Team Alchemy"
                );
                // $this->sendSms($params); //check 

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'Welcome email and sms sent',
                    'author' => $admin->admin_name
                ]);

                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function sendToWaitingList($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (!empty($job)) {
                if ($job->job_status != 0) throw new \Exception('The job is not active.');
                $job->update([
                    'job_status' => 3,
                    'last_updated_for_waiting_list' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);

                $parent = $job->parent;
                $child = $job->child;
                $child_name_arr = explode(' ', $child->child_name);
                $admin = auth()->user()->admin;
                $admin_name_arr = explode(' ', $admin->admin_name);

                $params = [
                    'studentfirstname' => $child_name_arr[0],
                    'parentfirstname' => $parent->parent_first_name,
                    'adminfirstname' => $admin_name_arr[0],
                    'adminname' => $admin->admin_name
                ];
                $this->sendEmail($parent->parent_email, 'send-to-waiting-list-notification-email', $params);

                $params = array(
                    'name' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                    'phone' => $parent->parent_phone,
                    'body' => "Hi " . $parent->parent_first_name . ", please check your email for an update on organising a tutor for " . $child_name_arr[0] . " - Team Alchemy"
                );
                // $this->sendSms($params); //check 

                $this->addJobHistory([
                    'job_id' => $job_id,
                    'comment' => 'The lead is sent to the waiting list',
                    'author' => $admin->admin_name
                ]);

                return true;
            } else {
                throw new \Exception('The job is not existed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get suitable tutors for job
     * @param int $job_id
     * @param int $tutors_length : number for tutors in online session
     * @return array 
     */
    public function getAutomationTutors($job_id, $tutors_length = 30)
    {
        $tutors = [];
        $distances = unserialize($this->getOption('job-min-distance')) ?? [];
        $max_distance = max($distances) ?? 0;
        $job = Job::where('job_status', 0)->where('hidden', 0)->where('id', $job_id)->first();
        if (!empty($job)) {
            $parent = $job->parent;
            $child = $job->child;
            $child_lat = $parent->parent_lat ?? 0;
            $child_lon = $parent->parent_lon ?? 0;
            if (!empty($parent) && !empty($child)) {
                if ($job->session_type_id == 1) {
                    $tutor_query = $this->AutomationTutorsQuery($job_id);
                    $temp_tutors = $tutor_query->get();
                    if (!empty($temp_tutors)) {
                        foreach ($temp_tutors as $tutor) {
                            $check = $this->checkRejectedAndAvailabilities($job, $tutor, $parent);
                            if (!$check) continue;

                            $tutor_lat = $tutor->lat ?? 0;
                            $tutor_lon = $tutor->lon ?? 0;
                            $distance = $this->calcDistance($child_lat, $child_lon, $tutor_lat, $tutor_lon);
                            $tutor->distance = $distance;
                            if ($distance > $max_distance) continue;
                            $tutors[] = $tutor;
                        }

                        if (!empty($tutors)) {
                            uasort($tutors, function ($a, $b) {
                                return ($a->distance >= $b->distance) ? 1 : -1;
                            });
                        }
                    }
                } else {
                    $i = 0;
                    $count_num = 0;
                    while ($i < 3) {
                        $i++;
                        if ($count_num >= $tutors_length) break;

                        $tutor_query = $this->AutomationTutorsQuery($job_id);
                        if ($i == 1) $tutor_query = $tutor_query->where('seeking_students', 1)
                            ->orderBy('non_metro', 'DESC')->orderBy('online_automation_timestamp', 'ASC')->orderBy('seeking_students_timestamp', 'DESC');
                        else if ($i == 2) $tutor_query = $tutor_query->whereRaw("TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 720")
                            ->orderBy('non_metro', 'DESC')->orderBy('seeking_students', 'DESC')->orderBy('created_at', 'DESC')->orderBy('seeking_students_timestamp', 'DESC');
                        else if ($i == 3)  $tutor_query = $tutor_query->orderBy('non_metro', 'DESC')->orderBy('seeking_students', 'DESC')->orderBy('online_automation_timestamp', 'ASC')->orderBy('seeking_students_timestamp', 'DESC');

                        $temp_tutors = $tutor_query->get();
                        if (!empty($temp_tutors)) {
                            foreach ($temp_tutors as $tutor) {
                                $check = $this->checkRejectedAndAvailabilities($job, $tutor, $parent);
                                if (!$check) continue;

                                $tutors[] = $tutor;
                                $count_num++;
                            }
                        }
                    }
                }
            }
        }

        return $tutors;
    }

    /**
     * Return tutor query builder for automating tutors.
     * @param mixed $job_id
     * @return Tutor
     */
    public function AutomationTutorsQuery($job_id)
    {
        $job = Job::where('job_status', 0)->where('hidden', 0)->where('id', $job_id)->first();
        if (!empty($job)) {
            $parent = $job->parent;
            $child = $job->child;
            if (!empty($parent) && !empty($child)) {
                $job_subject = $job->subject;
                if ($job->session_type_id == 2) {
                    if (stripos($job_subject, 'support') !== false) $job_subject = 'support';
                    if ($job_subject == 'English') $job_subject = ',English,';
                }
                $tutor_query = Tutor::where('expert_sub', 'like', "%" . $job_subject . "%")
                    ->where('tutor_status', 1)->where('accept_job_status', 1);

                if ($job->session_type_id == 1) {
                    if (!!$job->vaccinated) $tutor_query = $tutor_query->where('vaccinated', 1);
                    if (!empty($parent->parent_state)) $tutor_query = $tutor_query->where('state', $parent->parent_state);
                }
                if (!!$job->experienced_tutor) $tutor_query = $tutor_query->where('experienced', 1);
                if (!empty($job->prefered_gender)) $tutor_query = $tutor_query->where('gender', $job->prefered_gender);

                $ignores = $this->getIgnoredTutorsForJob($job_id);
                if (!empty($ignores) && count($ignores) == 0) $tutor_query = $tutor_query->whereNotIn('id', $ignores);

                return $tutor_query;
            }
        }

        return null;
    }

    /**
     * Return true if does not exist in rejected_jobs and match availabilities from job and tutor, else return false.
     * @param mixed $job
     * @param mixed $tutor
     * @param mixed $parent
     * @return bool
     */
    public function checkRejectedAndAvailabilities($job, $tutor, $parent)
    {
        $rejected_jobs = $tutor->rejected_jobs;
        if (!empty($rejected_jobs) && !empty($rejected_jobs->rejected)) {
            if (in_array($job->id, $rejected_jobs->rejected)) return false;
        }

        $av = false;
        if ($job->session_type_id == 2 && empty($job->date)) $av = true;
        else {
            $availabilities = $tutor->availabilities_2;
            $hour_diff = $this->getTimezoneDiffHours($parent->id, $tutor->id);
            $av_str = $job->date ?? '';
            if (!empty($hour_diff)) $av_str = $this->convertTimezone($av_str, $hour_diff);
            $job_availabilities = $this->getAvailabilitiesFromString($av_str);
            foreach ($job_availabilities as $job_av) {
                if (in_array($job_av, $availabilities)) $av = true;
            }
        }
        if (!$av) return false;

        if ($job->session_type_id == 1 && $tutor->under_18) return false;

        return true;
    }

    public function makeJobToWaitingList($job_id)
    {
        $job = Job::find($job_id);
        if (!empty($job)) {
            $job->update([
                'job_status' => 3,
                'last_updated_for_waiting_list' => (new \DateTime('now'))->format('d/m/Y H:i')
            ]);

            $sms_params = [
                'name' => $job->parent->parent_first_name ?? '',
                'phone' => $job->parent->parent_phone ?? '',
            ];
            $sms_body = "Please check your email regarding your booking request for a tutor - Team Alchemy";
            $this->sendSms($sms_params, $sms_body);

            $subject = "Tutoring for " . $job->child->first_name;
            $body = "Hi " . $job->parent->parent_first_name . ", <br /><br />Nadine from Alchemy here - hope you are well.<br><br>I just wanted to send through an update on organising a tutor for " . $job->child->first_name . ".<br><br>Despite our best efforts, we have regretfully not been able to pair them up with a matching tutor and think it would be best to move your request to our priority waiting list.<br><br>From here, if a great matching tutor comes along we will email you to let you know their availabilities and profile, and you can choose if you would like to go ahead with the first lesson. <br><br>I am really sorry that we couldn't confirm a lesson right away - but by keeping " . $job->child->first_name . " on our priority waiting list we will be able to notify you as great tutors become available and give you the opportunity to get started with a tutor then.<br><br>We will keep " . $job->child->first_name . " on our priority waiting list for 3 months - you can also request to be removed anytime we send you tutor details.<br><br>If you have any questions please let me know!<br><br>Nadine<br>Alchemy Tuition";
            $this->sendEmail($job->parent->parent_email, $subject, null, $body);
        }
    }
    /**
     * Return true when the hour is in 7 - 22 o'clock, else return false.
     * @return bool
     */
    public function isWorkingHours()
    {
        $datetime = new \DateTime();
        $hour = $datetime->format('H');
        if ($hour < 7 || $hour >= 22) return false;
        return true;
    }
}
