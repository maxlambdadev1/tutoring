<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Models\HolidayStudent;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class NewYearNoLessonScheduled extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-year-no-lesson-scheduled';

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
        $option = $this->getOption('new-year-no-lesson-scheduled-cron');
        $current_year = date("Y"); 
        if ($option == 1) {
            $holiday_students = HolidayStudent::where('year', date('Y'))->where('status', 3)->whereRaw("TIMESTAMPDIFF(HOUR, updated_at, NOW()) >= 80")->get();
            foreach ($holiday_students as $holiday_student) {
                $next_session = Session::where('tutor_id', $holiday_student->last_tutor)->where('child_id', $holiday_student->child_id)
                    ->where(function ($query) {
                        $query->where('session_status', 3)->orWhere('session_status', 1);
                    })
                    ->orderBy('id', 'DESC')->first();
                if (!empty($next_session)) continue;

                $finished_session = Session::where('tutor_id', $holiday_student->last_tutor)->where('child_id', $holiday_student->child_id)
                    ->where('session_status', 2)
                    ->whereRaw("TIMESTAMPDIFF(HOUR, '" . $current_year . "-01-10" . "', updated_at) > 0")
                    ->orderBy('id', 'DESC')->first();
                if (!empty($finished_session)) continue;

                $child = $holiday_student->child;
                $tutor = $holiday_student->tutor;
                $params = [
                    'email' => $tutor->tutor_email,
                    'tutorfirstname' => $tutor->first_name,
                    'studentname' => $child->child_name,
                ];
                $this->sendEmail($params['email'], "new-year-no-lesson-scheduled-email", $params);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $holiday_student->id,
                    'comment' => "Reminder to schedule lesson email sent to tutor."
                ]);
            }
        }
    }
}
