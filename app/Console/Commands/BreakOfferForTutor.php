<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class BreakOfferForTutor extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:break-offer-for-tutor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset break_count if the value is over 5 in each tutors.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tutors = Tutor::where('tutor_status', 1)->where('accept_job_status', 1)->where('break_count', '>=', 5)->get();
        foreach ($tutors as $tutor) {
            $tutor->update([
                'break_count' => 0
            ]);

            $secret = sha1($tutor->id . env('SHARED_SECRET'));
            $link = $this->setRedirect("https://" . env('TUTOR') . "/opt-out?url=" . base64_encode('secret=' . $secret . '&tutor_id=' . $tutor->id));
            $sms_params = [
                'name' => $tutor->tutor_name,
                'phone' => $tutor->tutor_phone,
            ];
            $body = "We've been sending you a lot of student offers lately! Want to take a break? Click here and opt out of student offers for a few weeks: " . $link;
            $this->sendSms($sms_params, $body, null, 1);
        }
    }
}
