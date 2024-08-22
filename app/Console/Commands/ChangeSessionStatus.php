<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\Job;
use Illuminate\Console\Command;

class ChangeSessionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-session-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update scheduled sessions status to 'unconfirmed' when scheduled date is passed today";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');

        $scheduled_sessions = Session::where('session_status', 3)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE('". $datetime . "', '%d/%m/%Y %H:%i'), STR_TO_DATE(CONCAT(session_date,' ',session_time), '%d/%m/%Y %H:%i')) > 0")
            ->update(['session_status' => 1]);

    }
}
