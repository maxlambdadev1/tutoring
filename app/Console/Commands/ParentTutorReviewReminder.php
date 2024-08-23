<?php

namespace App\Console\Commands;

use App\Models\SessionProgressReport;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class ParentTutorReviewReminder extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parent-tutor-review-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send 'parent-tutor-review-reminder-email' to parent everyday if questions is empty and count < 3 in session_progress_report table";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $reports = SessionProgressReport::whereNotNull('q1')
            ->whereNotNull('q2')
            ->whereNotNull('q3')
            ->where('review_reminder', 0)
            ->where('reminder_count', '<', 3)
            ->get();
            
        foreach ($reports as $report) {
            $report->increment('reminder_count');
            $parent = $report->parent;
            $child = $report->child;
            $tutor = $report->tutor;
            $ukey = base64_encode(serialize([
                'tutor_id' => $tutor->id,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'tutor_name' => $tutor->tutor_name,
                'child_name' => $child->first_name,
                'report_id' => $report->id,
            ]));
            $link = "https://" . env('TUTOR') . "/tutor-review?key=" . $ukey;

            $params = [
                'studentname' => $child->first_name,
                'parentfirstname' => $parent->parent_first_name,
                'email' => $parent->parent_email,
                'link' => $link,
            ];
            $this->sendEmail($params['email'], "parent-tutor-review-reminder-email", $params);
        }
    }
}
