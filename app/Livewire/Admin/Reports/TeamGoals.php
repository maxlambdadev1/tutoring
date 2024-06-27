<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Goal;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class TeamGoals extends Component
{
    public $prev = 0;
    public $goals = [];

    public $year;
    public $quarter;
    public string $month;
    public $goal_start;
    public $goal_current;
    public $last_updated;
    public $quarter_info = [
        1 => ['01', '02', '03'],
        2 => ['04', '05', '06'],
        3 => ['07', '08', '09'],
        4 => ['10', '11', '12']
    ];
    public $months_str_arr = [
        '01' => 'January',
        '02' => 'Febrary', 
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    ];

    public function mount()
    {
        $year = date("Y");
        $month = date('m'); 
        foreach ($this->quarter_info as $key => $value) {
            if (in_array($month, $value)) {
                $quarter = $key;
                if ($this->prev == 1) {
                    $quarter = $quarter - 1;
                    if ($quarter == 0) {
                        $quarter = 4;
                        $year = (int)$year - 1;
                    }
                }
            }
        }
        
        $this->year = 2020;// $year;
        $this->quarter = 3; //$quarter;
        $this->month = '08';
        // $this->year = $year;
        // $this->quarter = $quarter;
        // $this->month = $month;
        $this->getGoals();
        $this->changeMonth();

        $last_updated_goal = Goal::orderBy('updated_at', 'desc')->first();
        $this->last_updated = $last_updated_goal->updated_at ?? '-';
    }

    public function changeYear($year) {
        $this->year = $year;
        $this->goal_start = '';
        $this->goal_current = '';
        $this->getGoals();
    }
    
    public function changeQuarter() {
        $this->month = '';
        $this->goal_start = '';
        $this->goal_current = '';
        $this->getGoals();
    }
    
    public function changeMonth() {
        $selected_goal = Goal::where('year', $this->year)->where('month', $this->month)->first();
        if (!empty($selected_goal)) {
            $this->goal_start = $selected_goal->goal_start;
            $this->goal_current = $selected_goal->goal_current;
        } else {
            $this->goal_start = '';
            $this->goal_current = '';
        }
    }

    public function getGoals()
    {
        if (!empty($this->year) && !empty($this->quarter)) {
            $months = $this->quarter_info[$this->quarter];
            $goals = [];
            foreach ($months as $month) {
                $goal = Goal::where('year', $this->year)->where('month', $month)->orderBy('month')->first();
                $goals[] = $goal;
            }
            $this->goals = $goals;
        } else {
            $this->goals = [];
        }
    }

    public function saveGoal() {        
        try {
            if (!empty($this->goal_start) && !empty($this->goal_current)) {
                Goal::updateOrCreate([
                    'year' => $this->year,
                    'quarter' => $this->quarter,
                    'month' => $this->month,
                ], [
                    'goal_start' => $this->goal_start,
                    'goal_current' => $this->goal_current
                ]);
                $this->getGoals();

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Saved successfully!'
                ]);
            } else throw new \Exception("Please input the goals.");

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.reports.team-goals');
    }
}
