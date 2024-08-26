<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\TutorWwccValidate;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class WwccAppCheck extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wwcc-app-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'tutor-expiry-email' for 6 weeks to NSW tutors that wwcc_number is empty.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $start_today = strtotime(date('Y-m-d') . ' 00:00:00');
        $end_today = strtotime(date('Y-m-d') . ' 23:59:59');

        $diff = 604800;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate);
        }

        $diff = 604800 * 2;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate);
        }

        $diff = 604800 * 3;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate);
        }

        $diff = 604800 * 4;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate, 4);
        }
        
        $diff = 604800 * 5;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate, 5);
        }
        
        $diff = 604800 * 6;
        $tutor_wwcc_validates = TutorWwccValidate::where('timestamp', '>=', $start_today - $diff)->where('timestamp', '<=', $end_today - $diff)->get();
        foreach ($tutor_wwcc_validates as $wwcc_validate) {
            $this->getTutor($wwcc_validate, 6);
        }
    }

    public function getTutor($wwcc_validate, $check = 0)
    {
        $tutor = $wwcc_validate->tutor;
        if (!empty($tutor) && $tutor->tutor_status == 1) {
            if ($tutor->state == 'NSW') {
                if (empty($tutor->wwcc_number) && !empty($tutor->wwcc_application_number)) {
                    $params = [
                        'tutirfirstname' => $tutor->first_name,
                        'email' => $tutor->tutor_email,
                    ];
                    $this->sendEmail($params['email'], "tutor-wwcc-expiry-email", $params);
                    $this->addTutorHistory([
                        'tutor_id' => $tutor->id,
                        'comment' => "The system sent WWCC application expiring email."
                    ]);

                    if ($check == 4)  $wwcc_validate->update(['4w_reminder' => 1]);
                    if ($check == 5)  $wwcc_validate->update(['5w_reminder' => 1]);
                    if ($check == 6)  $wwcc_validate->update(['6w_reminder' => 1]);
                }
            }
        }
    }
}
