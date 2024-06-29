<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Job;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\TutorApplication;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class MonthlyReport extends Component
{
    public $isLoading = false;
    public $from_date;
    public $to_date;
    public $from_date_last_year;
    public $to_date_last_year;
    public $data = [];

    public function mount()
    {
        $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
        $this->to_date = $today->format('d/m/Y');
        $this->from_date = '01/' . $today->format('m/Y');
        $this->getData($this->from_date, $this->to_date);
    }

    /**
     * get data from current from_date, to_date
     * @param $from_date : string - 'd/m/Y' , $to_date : string - 'd/m/Y'
     */
    public function getData($from_date, $to_date)
    {
        $from = \DateTime::createFromFormat('d/m/Y', $from_date);
        $from_date_last_year = $from->modify('-1 year')->format('d/m/Y');
        $to = \DateTime::createFromFormat('d/m/Y', $to_date);
        $to_date_last_year = $to->modify('-1 year')->format('d/m/Y');
        $this->from_date_last_year = $from_date_last_year;
        $this->to_date_last_year = $to_date_last_year;

        $current_from_date = \DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        $current_to_date = \DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');

        $past_from_date = \DateTime::createFromFormat('d/m/Y', $from_date_last_year)->format('Y-m-d');
        $past_to_date = \DateTime::createFromFormat('d/m/Y', $to_date_last_year)->format('Y-m-d');

        $data['total_bookings']['past'] = Job::whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', created_at) <= 0")->count();
        $data['total_bookings']['current'] = Job::whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', created_at) <= 0")->count();

        $data['total_conversion_bookings']['past'] = Job::where('job_status', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', created_at) <= 0")->count();
        $data['total_conversion_bookings']['current'] = Job::where('job_status', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', created_at) <= 0")->count();

        $data['first_session_total']['past'] = Session::where('session_is_first', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_total']['current'] = Session::where('session_is_first', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['first_session_scheduled']['past'] = Session::where('session_is_first', 1)->where('session_status', 3)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_scheduled']['current'] = Session::where('session_is_first', 1)->where('session_status', 3)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['first_session_confirmed']['past'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)->whereIn('session_status', [2, 4, 5])
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_confirmed']['current'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)->whereIn('session_status', [2, 4, 5])
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['first_session_unconfirmed']['past'] = Session::where('session_is_first', 1)->where('session_status', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_unconfirmed']['current'] = Session::where('session_is_first', 1)->where('session_status', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['first_session_cancelled']['past'] = Session::where('session_is_first', 1)
            ->whereRaw('(((session_status=4 OR session_status=5) AND (session_length <= 0 OR session_length IS NULL)) OR session_status=6)')
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_cancelled']['current'] = Session::where('session_is_first', 1)
            ->whereRaw('(((session_status=4 OR session_status=5) AND (session_length <= 0 OR session_length IS NULL)) OR session_status=6)')
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['first_session_success']['past'] = Session::where('session_is_first', 1)
            ->where('session_status', 2)
            ->whereNotNull('session_next_session_id')
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['first_session_success']['current'] = Session::where('session_is_first', 1)
            ->where('session_status', 2)
            ->whereNotNull('session_next_session_id')
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['total_sessions_f2f']['past'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)
            ->whereIn('session_status', [2, 4, 5])
            ->where('type_id', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['total_sessions_f2f']['current'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)
            ->whereIn('session_status', [2, 4, 5])
            ->where('type_id', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['total_sessions']['past'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)
            ->whereIn('session_status', [2, 4, 5])
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();
        $data['total_sessions']['current'] = Session::where('session_is_first', 1)
            ->where('session_length', '>', 0)
            ->whereIn('session_status', [2, 4, 5])
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <= 0")->count();

        $data['total_hours']['past'] = DB::select("SELECT SUM(session_length) AS total FROM alchemy_sessions 
        	WHERE (session_status = 2 OR ((session_status=4 OR session_status=5) AND session_length > 0)) AND
        	TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >=0 
        	AND TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <=0")[0]->total ?? 0;
        $data['total_hours']['current'] = DB::select("SELECT SUM(session_length) AS total FROM alchemy_sessions 
                WHERE (session_status = 2 OR ((session_status=4 OR session_status=5) AND session_length > 0)) AND
                TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >=0 
                AND TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <=0")[0]->total ?? 0;

        $data['total_tutor_applicants']['past'] = TutorApplication::query()
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('alchemy_tutor_application_status as s')
                    ->whereRaw('s.application_id = alchemy_tutor_application.id')
                    ->where('s.application_status', '!=', 7);
            })
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', created_at) <= 0")->count();

        $data['total_tutor_applicants']['current'] = TutorApplication::query()
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('alchemy_tutor_application_status as s')
                    ->whereRaw('s.application_id = alchemy_tutor_application.id')
                    ->where('s.application_status', '!=', 7);
            })
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', created_at) <= 0")->count();

        $data['new_tutors_registered']['past'] = Tutor::query()
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', created_at) <= 0")->count();
        $data['new_tutors_registered']['current'] = Tutor::query()
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', created_at) <= 0")->count();

        $data['tutors_inactive']['past'] = Tutor::where('tutor_status', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', created_at) <= 0")->count();
        $data['c']['current'] = Tutor::where('tutor_status', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', created_at) >= 0")
            ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', created_at) <= 0")->count();

        $data['revenue']['past'] = DB::select("SELECT SUM(session_length * (session_price - session_tutor_price)) AS total FROM alchemy_sessions
			WHERE (session_status = 2 OR ((session_status=4 OR session_status=5) AND session_length > 0)) AND
        	TIMESTAMPDIFF(SECOND, '" . $past_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >=0 
        	AND TIMESTAMPDIFF(SECOND, '" . $past_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <=0")[0]->total ?? 0;
        $data['revenue']['current'] = DB::select("SELECT SUM(session_length * (session_price - session_tutor_price)) AS total FROM alchemy_sessions
			WHERE (session_status = 2 OR ((session_status=4 OR session_status=5) AND session_length > 0)) AND
                TIMESTAMPDIFF(SECOND, '" . $current_from_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >=0 
                AND TIMESTAMPDIFF(SECOND, '" . $current_to_date . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <=0")[0]->total ?? 0;

        $data['revenue_percentage'] = $data['revenue']['current'] > 0 ? round(100 * ($data['revenue']['current'] - $data['revenue']['past']) /$data['revenue']['current']) : 0;

        $this->isLoading = false;
        $this->data = $data;
    }

    public function viewReport($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->getData($this->from_date, $this->to_date);
    }

    public function render()
    {
        return view('livewire.admin.reports.monthly-report');
    }
}
