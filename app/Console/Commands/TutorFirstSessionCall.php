<?php

namespace App\Console\Commands;

use App\Models\TutorFirstSession;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorFirstSessionCall extends Command
{
    use Mailable, Functions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-first-session-call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send first session email to tutor.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $follows = TutorFirstSession::where('status', 1)->where('email_sent', 0)->get();
        foreach ($follows as $follow) {
            $tutor = $follow->tutor;
            if (!empty($tutor)) {
                $params = [
                    'email' => $tutor->tutor_email,
                    'tutorfirstname' => $tutor->first_name,
                ];
                $this->sendEmail($params['email'], "tutor-first-student-email", $params);
                $tutor->update([
                    "email_sent" => 1,
                    "date_last_update" => date('d/m//Y H:i'),
                ]);

                $this->addTutorHistory([
                    'tutor_id' => $tutor->id,
                    'comment' => "Sent 'First Lesson Mentor Call' invite email 24 hours after accepting first student."
                ]);
            }
        }
    }
}
