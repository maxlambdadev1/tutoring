<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\TutorSpecialReferralEmail;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorSpecialReferral extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-special-referral';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send referral email to tutors that created in 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = new \DateTime('now');
        $referrals = TutorSpecialReferralEmail::whereRaw("DATEDIFF(curdate(), created_at) <= 30")->get();
        foreach ($referrals as $ref) {
            $tutor = $ref->tutor;
            if (!empty($tutor)) {
                $params = [
                    'tutorfirstname' => $tutor->first_name,
                    'referralspecialprice' => $this->getOption('referral-special-amount'),
                    'referralprice' => $this->getOption('referral-amount'),
                    'email' => $tutor->tutor_email,
                    'referralcode' => "1" . $tutor->id,
                ];
                $created_at = \DateTime::createFromFormat('d/m/Y', trim($tutor->tutor_creat));
                if ($today > $created_at) {
                    $interval = $today->diff($created_at);
                    if ($interval->days > 30) continue;

                    if ($interval->days >= 2 && $ref->reminder1 == 0) {
                        $ref->update(['reminder1' => 1]);
                        $this->sendEmail($params['email'], "tutor-special-promo1-email", $params);
                    } else if ($interval->days >= 16 && $ref->reminder2 == 0) {
                        $ref->update(['reminder2' => 1]);
                        $this->sendEmail($params['email'], "tutor-special-promo2-email", $params);
                    } else if ($interval->days >= 16 && $ref->reminder3 == 0) {
                        $ref->update(['reminder3' => 1]);
                        $this->sendEmail($params['email'], "tutor-special-promo3-email", $params);
                    }
                }
            }
        }
    }
}
