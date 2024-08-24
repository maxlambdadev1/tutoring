<?php

namespace App\Console\Commands;

use App\Models\TutorApplication;
use App\Models\TutorApplicationQueue;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use App\Trait\WithTutors;
use Illuminate\Console\Command;

class TutorApplicationFollowup extends Command
{
    use Functions, Mailable, WithLeads, WithTutors;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-application-followup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Action for tutor applications that application_status is 2 or 4';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $now = new \DateTime();
        $three_days_ago = $now->modify('-3 days');
        $three_days_ago_str = $three_days_ago->format('d/m/Y H:i');

        $applications = TutorApplication::query()
            ->join('alchemy_tutor_application_status', function ($status) {
                $status->on('alchemy_tutor_application_status.application_id', '=', 'alchemy_tutor_application.id');
            })
            ->where('application_status', 2)
            ->orWhere('application_status', 4)
            ->select('alchemy_tutor_application.*')
            ->get();

        foreach ($applications as $app) {
            $status = $app->application_status;
            $status->increment('followup_counter');
            if (empty($status->date_follow_up)) {
                $app->update(['date_last_update' => $three_days_ago_str]);
            }
            if (empty($status->date_last_update)) {
                $status->update(['date_last_update' => $three_days_ago_str]);
            }
            $datetime = \DateTime::createFromFormat('d/m/Y H:i', $status->date_last_update);
            $interval = $datetime->diff($now);
            if ($interval->m < 1 && $interval->d < $status->followup_counter * 3) continue;

            if ($status->application_status == 2) {
                if ($status->followup_counter < 6) {
                    if ($status->followup_counter < 5) {
                        $sms_params = [
                            'name' => $app->tutor_name,
                            'phone' => $app->tutor_phone,
                        ];
                        $body = "Reminder: schedule your tutor interview with Alchemy tuition at " . env('MAIN_SITE') . "/tutor-interview.";
                        $this->sendSms($sms_params, $body);
                        if ($status->followup_counter == 3) {
                            $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "'Scheduling interview' sms");
                        } else {
                            $params = [
                                'email' => $app->tutor_email,
                                'tutorfirstname' => $app->tutor_first_name,
                            ];
                            $this->sendEmail($params['email'], "tutor-application-interview-reminder", $params);
                            $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "'Scheduling interview' email and sms");
                        }
                    } else { // == 5
                        $params = [
                            'email' => $app->tutor_email,
                            'tutorfirstname' => $app->tutor_first_name,
                        ];
                        $this->sendEmail($params['email'], "tutor-application-interview-reminder-final", $params);

                        $sms_params = [
                            'name' => $app->tutor_name,
                            'phone' => $app->tutor_phone,
                        ];
                        $body = "inal reminder: would you still like to tutor with us? This is your last chance to schedule your Alchemy interview at  " . env('MAIN_SITE') . "/tutor-interview. If you have any questions please reply to the message and we will respond ASAP - Team Alchemy.";
                        $this->sendSms($sms_params, $body);

                        $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "'Scheduling interview' final email and sms");
                    }
                } else { // >= 6
                    $this->acceptTutorApplication([
                        'application_id' => $app->id,
                        'status' => 9
                    ]);
                    $this->addTutorApplicationHistory([
                        'application_id' => $app->id,
                        'comment' => "Sent from 'Scheduling interview' to 'Close' due to too many followups."
                    ]);
                }
            } else if ($status->application_status == 4) {
                if ($status->followup_counter < 6) {
                            $secret = sha1($app->id . env('SHARED_SECRET'));
                    if ($status->followup_counter < 5) {
                        $sms_params = [
                            'name' => $app->tutor_name,
                            'phone' => $app->tutor_phone,
                        ];
                        $body = "Reminder: Register as an Alchemy Tutor and get started with your first student! Check your email for next steps and more info";
                        $this->sendSms($sms_params, $body);
                        if ($status->followup_counter == 3) {
                            $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "'Awaiting register' sms");
                        } else {
                            $params = [
                                'email' => $app->tutor_email,
                                'tutorfirstname' => $app->tutor_first_name,
                                'registerurl' => "https://" . env('TUTOR') . "/register?url=" . base64_encode("secret=" . $secret . "&application_id=" . $app->id)
                            ];
                            $this->sendEmail($params['email'], "tutor-application-register-reminder", $params);
                            $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "'Awaiting register' email and sms");
                        }
                    } else { // == 5
                        $params = [
                            'email' => $app->tutor_email,
                            'tutorfirstname' => $app->tutor_first_name,
                            'registerurl' => "https://" . env('TUTOR') . "/register?url=" . base64_encode("secret=" . $secret . "&application_id=" . $app->id)
                        ];
                        $this->sendEmail($params['email'], "tutor-application-register-reminder-final", $params);

                        $sms_params = [
                            'name' => $app->tutor_name,
                            'phone' => $app->tutor_phone,
                        ];
                        $body = "Final reminder: it is your last day to register as an Alchemy tutor. We would love to get you going, so please check your email for next steps. If you have any questions please reply to this message and we will respond ASAP!";
                        $this->sendSms($sms_params, $body);

                        $this->updateTutorApplicationFollowup($app->id, $status->followup_counter, "Awaiting register' final email and sms");
                    }
                } else { // >= 6
                    $this->acceptTutorApplication([
                        'application_id' => $app->id,
                        'status' => 9
                    ]);
                    $this->addTutorApplicationHistory([
                        'application_id' => $app->id,
                        'comment' => "Sent from 'Awaiting to register' to 'Close' due to too many followups."
                    ]);
                }
            }
        }
    }
}
