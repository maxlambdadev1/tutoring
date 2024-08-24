<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\BookingTarget;
use App\Models\ConversionTarget;
use App\Models\ReportDaily;
use App\Models\Session;
use Illuminate\Console\Command;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = new \DateTime();
        $this->dailyReport($datetime);
        // $this->updatePastDailyReport($datetime);
    }

    public function dailyReport($datetime) {
        $date = $datetime->format('d/m/Y');

        $bookings = 0;
        $conversions = 0;
        $leads_in_system = 0;

        $bookings = BookingTarget::where('booking_date', $date)->count();
        $conversions = ConversionTarget::where('conversion_date', $date)->count();
        $tutor_conversions = ConversionTarget::where('conversion_date', $date)->where('converted_by', 'tutor')->count();
        $team_conversions = ConversionTarget::where('conversion_date', $date)->where('converted_by', 'admin')->count();

        $leads_in_system = Job::where('job_status', 0)->count();
        $total_confirmed_sessions = Session::whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') >= STR_TO_DATE('" . $date . " 00:00" . "', '%d/%m/%Y')")->where('session_status', 2)->count();
        $confirmed_first_sessions = Session::whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') >= STR_TO_DATE('" . $date . " 00:00" . "', '%d/%m/%Y')")->where('session_status', 2)->where('session_is_first', 1)->count();
        $total_confirmed_hours = Session::whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') >= STR_TO_DATE('" . $date . " 00:00" . "', '%d/%m/%Y')")->where('session_status', 2)->sum('session_length');

        ReportDaily::updateOrCreate([
            'date' => $date,
        ], [
            'bookings' => $bookings,
            'conversions' => $conversions,
            'tutor_conversions' => $tutor_conversions,
            'team_conversions' => $team_conversions,
            'total_confirmed_sessions' => $total_confirmed_sessions,
            'confirmed_first_sessions' => $confirmed_first_sessions,
            'total_confirmed_hours' => $total_confirmed_hours,
            'leads_in_system' => $leads_in_system,
            'date_last_updated' => $datetime->format('d/m/Y H:i'),
            'day' => $datetime->format('l')
        ]);
    }

    public function updatePastDailyReport($datetime) {
        $past_reports = ReportDaily::where('date' , '<>', $datetime->format('d/m/Y'))->get();
        foreach ($past_reports as $report) {
            $report_date = \DateTime::createFromFormat('d/m/Y', $report->date);
            $this->dailyReport($report_date);
        }
    }
}
