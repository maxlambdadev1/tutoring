<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class WwccExpiryCheck extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wwcc-expiry-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'working-with-children-check-expiry-email' to tutors that wwcc expiry is before 14 or 30 days.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = new \DateTime('now');
        $tutors = Tutor::where('tutor_status', 1)->get();
        foreach ($tutors as $tutor) {
            $expiry = \DateTime::createFromFormat('d/m/Y', trim($tutor->wwcc_expiry));
            if ($expiry > $today) continue;

            $interval = $today->diff($expiry);
            $params = [
                'email' => $tutor->tutor_email,
                'tutorfirstname' => $tutor->first_name,
            ];
            if ($interval->days == 30 || $interval->days == 14) {
                $this->sendEmail($params['email'], "working-with-children-check-expiry-email", $params);
                $this->addTutorHistory([
                    'tutor_id' => $tutor->id,
                    'comment' => "The system sent WWCC number expiring email."
                ]);
            }
        }
    }
}
