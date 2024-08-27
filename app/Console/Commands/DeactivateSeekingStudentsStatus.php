<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class DeactivateSeekingStudentsStatus extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-seeking-students-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deactivate after 14 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tutors = Tutor::where('seeking_students', 1)->where('tutor_status', 1)->get();
        foreach ($tutors as $tutor) {
            if (empty($tutor->seeking_students_timestamp) || (time() - $tutor->seeking_students_timestamp) / 86400 >= 14) {
                $tutor->update(['seeking_students' => 0]);
                $sms_params = [
                    'name' => $tutor->tutor_name,
                    'phone' => $tutor->tutor_phone,
                ];
                $body = "Your 'actively seeking students' status has now expired. If you'd like to reactivate it for another 2 weeks and keep receiving priority job offers, please log into your Dashbaord and hit the toggle at the top.";
                $this->sendSms($sms_params, $body);
            }
        }
    }
}
