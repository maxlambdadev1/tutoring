<?php

namespace App\Console\Commands;

use App\Models\AlchemyParent;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class PaymentInfoFollowup extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-info-followup';

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
        $ref_date = new \DateTime("2024-04-05");

        $query = AlchemyParent::query()
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('alchemy_sessions.parent_id', '=', 'alchemy_parent.id');
            })
            ->whereNull('stripe_customer_id')
            ->where('session_status', 2)
            ->having('total_sessions', '>=', 1)
            ->groupBy('alchemy_parent.id');

        $query->select('alchemy_parent.*',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
        $parents  = $query->get();
        foreach ($parents as $parent) {
            $child = $parent->child;
            if (!empty($child)) {
                $created_at = new \DateTime($child->created_at);
                if ($created_at < $ref_date) continue;

                if ($parent->follow_up == 0) {
                    $params = [
                        'parentfirstname' => $parent->parent_first_name,
                        'studentname' => $child->first_name,
                        'email' => $parent->parent_email,
                        'link' => "https://" . env('TUTOR') . "/paymentcc?email=" . $parent->parent_email,
                    ];
                    $this->sendEmail($params['email'], "parent-payment-details-email", $params);
                    $parent->update(['follow_up' => 1]);
                    $comment = "Sent request payment email to " . $parent->parent_email;
                } else {
                    $smsParams = [
                        'name' => $parent->parent_name,
                        'phone' => $parent->parent_phone,
                    ];
                    $body = "Hi " . $parent->parent_first_name . ", please submit your payment details ahead of your next lesson - you only need to do this once and will take less than a minute: https://" . env('TUTOR') . "/paymentcc?email=" . $parent->parent_email . ". Team Alchemy";
                    $this->sendSms($smsParams, $body);
                    $parent->update(['follow_up' => 0]);
                    $comment = "Sent request payment sms to " . $parent->parent_email;
                }

                $this->addStudentHistory([
                    'child_id' => $child->id,
                    'comment' => $comment,
                ]);
            }
        }
    }
}
