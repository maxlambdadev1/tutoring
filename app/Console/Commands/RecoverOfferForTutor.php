<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use Illuminate\Console\Command;

class RecoverOfferForTutor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recover-offer-for-tutor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover tutor break when break_date is before today.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = new \DateTime();
        $tutors = Tutor::where('tutor_status', 1)->where('accept_job_status', 0)->get();
        foreach ($tutors as $tutor) {
            if (!empty($tutor->break_date)) {
                $break_date = \DateTime::createFromFormat('d/m/Y', $tutor->break_date);
                if ($today > $break_date) {
                    $tutor->update([
                        'accept_job_status' => 1,
                        'break_date' => null,
                        'break_count' => 0,
                    ]);
                }
            }
        }
    }
}
