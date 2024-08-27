<?php

namespace App\Console\Commands;

use App\Models\HolidayReachParent;
use App\Models\HolidayStudent;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class NewYearReachParent extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-year-reach-parent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $reach_parents = HolidayReachParent::whereRaw("TIMESTAMPDIFF(HOUR, updated_at, NOW()) >= 72")->get();
        foreach ($reach_parents as $reach_parent) {
            $holiday_student = HolidayStudent::where('id', $reach_parent->id)->first();
            $child = $holiday_student->child;
            $parent = $child->parent;
            $secret = sha1($reach_parent->holiday_id) . env('SHARED_SECRET');
            $params = [
                'email' => $parent->parent_email,
                'parentfirstname' => $parent->parent_first_name,
                'studentname' => $child->first_name,
                'link' => "https://" . env('TUTOR') . "/replacement-tutor?key=" . $reach_parent->link,
                'nolink' => "https://" . env('TUTOR') . "/thankyou-parent?url=" . base64_encode("secret=" . $secret . "&id=" . $reach_parent->holiday_id),
            ];
            $sms_params = [
                'name' => $parent->parent_name,
                'phone' => $parent->parent_phone,
            ];
            if ($reach_parent->sms_first == 0) {
                $body = "Hi " . $parent->parent_first_name . ", Alchemy Tuition here. We'd love to organise a great tutor for " . $child->first_name . " to give them the best support this year. All we need are your availabilities and we can get it sorted - it will take less than 30 seconds: " . $this->setRedirect($params['link']) . ". If you don't need a tutor please click here: " . $this->setRedirect($params['nolink']);
                $this->sendSms($sms_params, $body);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $reach_parent->id,
                    'comment' => "SMS-1 reach out sent to parent."
                ]);
                $reach_parent->update(['sms_first' => 1]);
            } else if ($reach_parent->email_first == 0) {
                $this->sendEmail($params['email'], "end-of-holidays-new-year-student-2-email", $params);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $reach_parent->id,
                    'comment' => "Email-2 reach out sent to parent."
                ]);
                $reach_parent->update(['email_first' => 1]);
            } else if ($reach_parent->sms_second == 0) {                
                $body = "Don't forget: secure your Alchemy tutor today and beat the term 1 rush! We have tutors ready to go - just let us know the days and times that work best and we will get it lined up: " . $this->setRedirect($params['link']) . ". If you don't need a tutor please click here: " . $this->setRedirect($params['nolink']);
                $this->sendSms($sms_params, $body);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $reach_parent->id,
                    'comment' => "SMS-2 reach out sent to parent."
                ]);
                $reach_parent->update(['sms_second' => 1]);
            } else if ($reach_parent->email_second == 0) {
                $this->sendEmail($params['email'], "end-of-holidays-new-year-student-3-email", $params);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $reach_parent->id,
                    'comment' => "Email-3 reach out sent to parent."
                ]);
                $reach_parent->update(['email_second' => 1]);
            }
        }
    }
}
