<?php

namespace App\Console\Commands;

use App\Models\TutorInactiveSchedule;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorMakeInactiveSchedule extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-make-inactive-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Make tutor's status inactive is timestamp is in today.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $start_today = strtotime(date('Y-m-d') . ' 00:00:00');
        $end_today = strtotime(date('Y-m-d') . ' 23:59:59');

        $schedules = TutorInactiveSchedule::where('timestamp', '<=', $end_today)->where('timestamp', '>=', $start_today)->get();
        foreach ($schedules as $schedule) {
            $tutor = $schedule->tutor;
            if (!empty($tutor)) {
                $tutor->update(['tutor_status' => 0]);
                if ($tutor->state == 'QLD') {
                    $params = [
                        'email' => $tutor->tutor_email,
                        'tutorname' => $tutor->tutor_name,
                    ];
                    $this->sendEmail($params['email'], "inactive-qld-tutor-email", $params);
                }
            }
            $schedule->delete();
            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'comment' => "Sent to inactive for reaching the followup."
            ]);
        }
    }
}
