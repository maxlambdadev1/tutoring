<?php

namespace App\Console\Commands;

use App\Models\HolidayReplacement;
use App\Models\ReplacementTutor;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class NewYearReplacementParent extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-year-replacement-parent';

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
        $option = $this->getOption('new-year-replacement-cron');
        if ($option == 1) {
            $holiday_replacements = HolidayReplacement::where('year', date('Y'))->whereRaw("TIMESTAMPDIFF(HOUR, updated_at, NOW()) >= 72")->get();
            foreach ($holiday_replacements as $replacement) {
                $replacement_tutor = ReplacementTutor::where('id', $replacement->replacement_id)->where('replacement_status', 2)->first();
                if (!empty($replacement)) {
                    $tutor = $replacement_tutor->tutor;
                    $parent = $replacement_tutor->parent;
                    $child = $replacement_tutor->child;

                    $secret = sha1($replacement->id) . env('SHARED_SECRET');
                    $params = [
                        'email' => $parent->paent_email,
                        'parentfirstname' => $parent->parent_first_name,
                        'tutorfirstname' => $tutor->first_name,
                        'studentname' => $child->first_name,
                        'link' => "https://" . env('TUTOR') . "/replacement-tutor?key=" . $replacement_tutor->parent_link,
                        'nolink' => "https://" . env('TUTOR') . "/thankyou-parent?url=" . base64_encode("secret=" . $secret . "&id=" . $replacement->id),
                    ];
                    $this->sendEmail($params['email'], "new-year-replacement-parent-email", $params);
                    $this->addHolidayReplacementHistory([
                        'holiday_id' => $replacement->id,
                        'comment' => "Replacement offer email sent to parent"
                    ]);
                } 
            }
        }
    }
}
