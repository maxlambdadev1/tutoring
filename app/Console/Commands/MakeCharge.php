<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCharge extends Command
{
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
    protected $description = 'This command will get a payment from a parent and make a payment for a tutor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $session_id = $this->argument('session_id');
        if ($session_id) {
            $this->makeCharge($session_id);
        } else {
            $this->findSessionsToBePaid();
        }
    }

    public function findSessionsToBePaid()
    {
        echo 'no sesson-id';
    }

    public function makeCharge($session_id)
    {
        echo $session_id;
    }
}
