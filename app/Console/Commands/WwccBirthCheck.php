<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class WwccBirthCheck extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wwcc-birth-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'working-with-children-check-email' to tutor that WWCC birth expiration is before 14 or 28 days.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = new \DateTime('now');
        $tutors = Tutor::where('tutor_status', 1)->get();
        foreach ($tutors as $tutor) {
            if (empty($tutor->wwcc_number) && empty($tutor->wwcc_application_number)) {
                if (empty($tutor->birthday)) continue;
                $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
                if ($birth > $today) continue;

                $interval = $today->diff($birth);
                if ($interval->y == 17) {
                    $params = [
                        'email' => $tutor->tutor_email,
                        'tutorfirstname' => $tutor->first_name,
                    ];
                    date_add($birth,date_interval_create_from_date_string("18 years"));
                    $diff = date_diff($birth, $today);
                    if ($diff->days == 28 || $diff->days == 14) {
                        $this->sendEmail($params['email'], "working-with-children-check-email", $params);
                        $this->addTutorHistory([
                            'tutor_id' => $tutor->id,
                            'comment' => "The system sent WWCC 18 expiring email."
                        ]);
                    }
                }
            }
        }
    }
}
