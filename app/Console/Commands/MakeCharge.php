<?php

namespace App\Console\Commands;

use App\Models\AlchemyParent;
use App\Models\Child;
use App\Models\Session;
use App\Models\Tutor;
use App\Trait\TutoringStripe;
use App\Trait\TutoringXero;
use App\Trait\Functions;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Stripe\PaymentIntent;

class MakeCharge extends Command
{
    use TutoringStripe, TutoringXero, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-charge {session_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will charge a payment from parent and make a payment for tutor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $session_id = $this->argument('session_id');
        $session_id ? $this->makeChargeOfSession($session_id) : $this->getSessionsToBePaid();
    }

    public function getSessionsToBePaid()
    {
        $dateFormat = $this->convertDateFormatString(env('DATABASE_DATE_FORMAT'));
        $sessions = Session::select('id')->where('session_status', 2)
            ->where('session_price', '>', 0)
            ->whereNull('session_charge_id')
            ->whereRaw("DATEDIFF(now(), STR_TO_DATE(session_date, '$dateFormat')) < 90")   // means 90 days
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE(session_last_changed, '$dateFormat %H:%i'), now()) >= 86400")    // means 24 hours (86400 seconds)
            ->get();
        if ($sessions->count() > 0) {
            foreach ($sessions as $session) {
                $this->makeChargeOfSession($session['id']);
            }
        }
    }

    public function makeChargeOfSession($session_id)
    {        
        $session = Session::find($session_id);
        $tutor = Tutor::find($session->tutor_id);
        $parent = AlchemyParent::find($session->parent_id);
        $child = Child::find($session->child_id);

        $sessionPrice = round($session->session_price * $session->session_length);
        $tutorPrice = round($session->session_tutor_price * $session->session_length - $session->session_penalty);
        
        if (empty($session['session_invoice_id'])) {
            $this->addInvoice();
            usleep(100000); // 100 ms delay
        }
        if (empty($session['session_invoice_id'])) {
            $this->addBill();
            usleep(100000); // 100 ms delay
        }
        if (!empty($tutor['tutor_stripe_user_id']) && !empty($parent['stripe_customer_id'])) {
            if (stripos($parent['stripe_customer_id'], 'manual') === false) {
                usleep(200000);
                $stripe = $this->getStripeInstance();
                $account = $stripe->accounts->retrieve($tutor->tutor_stripe_user_id);
                if ($account->charges_enabled) {
                    $paymentArray = [
                        'amount' => $sessionPrice * 100,
                        'currency' => env('CURRENCY'),
                        'customer' => $parent->stripe_customer_id,
                        'metadata' => [
                            'session_id' => $session->session_id,
                            'sessiondate' => $session->session_date,
                            'sessiontime' => $session->session_time,
                            'parentname' => $parent->parent_name,
                            'studentname' => $child->child_name,
                        ],
                        'description' => "Private tuition for $child->child_name \r\nSession Date: $session->session_date\r\nSession Length: $session->session_length hour(s)\r\nABN: 88606073367",
                        'confirm' => true,
                    ];
                    if (empty($session->session_transfer_id)) {
                        array_push($paymentArray, [
                            'amount' => $tutorPrice * 100,
                            'destination' => $tutor->tutor_stripe_user_id,
                        ]);
                    }
                    $customer = $stripe->customers->retrieve($parent->stripe_customer_id);
                    if (!$customer->default_source) $paymentArray += ['payment_method' => $customer->invoice_settings->defalut_payment_method];
                    $paymentIntent = $stripe->paymentIntents->create($paymentArray);
                    if ($paymentIntent->status === PaymentIntent::STATUS_SUCCEEDED) {
                        $paymentIntentId = $paymentIntent->id;
                        $chargeId = $paymentIntent->latest_charge;
                        if (empty($session->session_transfer_id)) {
                            $transferId = NULL;
                            $transfer = $stripe->transfers->all([
                                'limit' => 1,
                                'transfer_group' => $paymentIntent->transfer_group,
                            ]);
                            if (count($transfer->data) > 0) $transferId = $transfer->data[0]->id;
                        }
                    }
                }
            } else {

            }
        } else {
            if (empty($tutor['tutor_stripe_user_id'])) {

            }
            if (empty($parent['stripe_customer_id'])) {

            }
        }
    }
}
