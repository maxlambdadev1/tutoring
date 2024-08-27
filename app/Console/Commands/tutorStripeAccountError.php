<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class tutorStripeAccountError extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-stripe-account-error';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'tutor-stripe-account-error-email' to tutor.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $tutors = Tutor::where('tutor_status', 1)->where('tutor_stripe_status', 0)->get();

        foreach ($tutors as $tutor) {
            $params = [
                'email' => $tutor->tutor_email,
                'tutorfirstname' => $tutor->first_name,
            ];
            $this->sendEmail($params['email'], "tutor-stripe-account-error-email", $params);
            
            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'comment' => "Sent 'stripe account error email'."
            ]);
        }
    }
}
