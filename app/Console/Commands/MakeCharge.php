<?php

namespace App\Console\Commands;

use App\Models\AlchemyParent;
use App\Models\Child;
use App\Models\Session;
use App\Models\Tutor;
use App\Trait\TutoringStripe;
use App\Trait\TutoringXero;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DateTime;

class MakeCharge extends Command
{
    use TutoringStripe, TutoringXero;
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
        $session_id ? $this->makeChargeOfSession($session_id) : $this->getToBePaidSessions();
    }

    public function getToBePaidSessions()
    {
        $sessions = Session::select('id')->where('session_status', 2)
            ->where('session_price', '>', 0)
            ->whereNull('session_charge_id')
            ->whereRaw("DATEDIFF(now(), STR_TO_DATE(session_date, '%d/%m/%Y')) < 90")   // means 90 days
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE(session_last_changed, '%d/%m/%Y %H:%i'), now()) >= 86400")    // means 24 hours (86400 seconds)
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
