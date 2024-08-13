<?php

namespace App\Livewire\Admin;

use App\Models\BookingTarget;
use App\Models\ConversionTarget;
use App\Models\Session;
use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Dashboard extends Component
{
    use Functions;

    public $total_sessions_week;
    public $total_sessions_previous;
    public $scheduled_sessions_week;
    public $scheduled_sessions_total;
    public $first_sessions_week;
    public $first_sessions_previous;
    public $week_stats = [];
    public $daily_booking_target;
    public $daily_conversion_target;
    public $daily_first_session_target;

    public function mount()
    {
        $this->total_sessions_week = Session::where('session_status', 2)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') between date_sub(now(),INTERVAL 8 DAY) and date_sub(now(), INTERVAL 1 DAY)")->count();
        $this->total_sessions_previous = Session::where('session_status', 2)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') between date_sub(now(),INTERVAL 15 DAY) and date_sub(now(), INTERVAL 9 DAY)")->count();
        $this->scheduled_sessions_total = Session::where('session_status', 3)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') >= curdate()")->count();
        $this->scheduled_sessions_week = Session::where('session_status', 3)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') between DATE_ADD(now(), INTERVAL 1 DAY) AND DATE_ADD(now(), INTERVAL 8 DAY)")->count();
        $this->first_sessions_week = Session::where('session_is_first', 1)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') between DATE_ADD(now(),INTERVAL 1 DAY) and DATE_ADD(now(), INTERVAL 8 DAY)")->count();
        $this->first_sessions_previous = Session::where('session_is_first', 1)->where('session_status', 2)
            ->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') between date_sub(now(),INTERVAL 8 DAY) and date_sub(now(), INTERVAL 1 DAY)")->count();

        $targets = $this->getOption('daily-target');
        $this->daily_booking_target = $targets['booking'] ?? 25;
        $this->daily_conversion_target = $targets['conversion'] ?? 25;
        $this->daily_first_session_target = $targets['first_session'] ?? 25;
        $datetime = new  \DateTime('Australia/Sydney');
        $counter = 0;
        while ($counter < 7) {
            $date_str = $datetime->format('d/m/Y');
            $item = ['date' => substr($datetime->format('l'), 0, 3)];
            $booking_target_from_booking = BookingTarget::where('booking_date', $date_str)
                ->where('source', 'booking')->count();
            $booking_target_from_team = BookingTarget::where('booking_date', $date_str)
                ->whereNot('source', 'replacement')->whereNot('source', 'booking')->count();
            $item['booking_target']['booking_form'] = $booking_target_from_booking;
            $item['booking_target']['team'] = $booking_target_from_team;
            $item['booking_target']['count'] = $booking_target_from_team + $booking_target_from_booking;
            
            $conversion_target_from_booking = ConversionTarget::query()
                ->join('alchemy_jobs', function ($job) {
                    $job->on('alchemy_conversion_target.job_id', '=', 'alchemy_jobs.id');
                })
                ->where('conversion_date', $date_str)
                ->whereNot('source', 'replacement')
                ->where('alchemy_conversion_target.converted_by', 'tutor')->count();
            $conversion_target_from_team = ConversionTarget::query()
            ->join('alchemy_jobs', function ($job) {
                $job->on('alchemy_conversion_target.job_id', '=', 'alchemy_jobs.id');
            })
            ->where('conversion_date', $date_str)
            ->whereNot('source', 'replacement')
            ->where('alchemy_conversion_target.converted_by', 'admin')->count();
            $item['conversion_target']['tutor'] = $conversion_target_from_booking;
            $item['conversion_target']['team'] = $conversion_target_from_team;
            $item['conversion_target']['count'] = $conversion_target_from_booking + $conversion_target_from_team;
            
            $first_session_target = Session::where('session_date', $date_str)
                ->where('session_is_first', 1)->count();
            $item['first_session_target']['count'] = $first_session_target;

            $result[] = $item;
            $counter++;
            $datetime->modify('-1 day');
        }
        $this->week_stats = $result;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
