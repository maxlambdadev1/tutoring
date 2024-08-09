<?php

namespace App\Livewire\Tutor;

use App\Models\Announcement;
use App\Models\Session;
use App\Models\Task;
use App\Models\Job;
use App\Models\RejectedJob;
use App\Trait\WithLeads;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class Dashboard extends Component
{
    use WithLeads;

    public $total_sessions;
    public $this_week_sessions;
    public $last_week_sessions;
    public $announcement;
    public $tasks = [];
    public $new_jobs = [];
    public $random_tasks = [];
    public $scheduled_sessions_events = [];
    public $under_18 = false;

    public function mount()
    {
        $tutor = auth()->user()->tutor;
        $this->total_sessions = Session::where('tutor_id', $tutor->id)
            ->where('session_status', 2)->count();
        $this->this_week_sessions = Session::where('tutor_id', $tutor->id)
            ->whereRaw("YEARWEEK(STR_TO_DATE(session_date, '%d/%m/%Y'), 1) = YEARWEEK(CURRENT_DATE(), 1)")->count();
        $this->last_week_sessions = Session::where('tutor_id', $tutor->id)
            ->whereRaw("YEARWEEK(STR_TO_DATE(session_date, '%d/%m/%Y'), 1) = YEARWEEK(CURRENT_DATE(), 1) - 1")->count();
        
        $this->announcement = Announcement::first();

        $scheduled_sessions = Session::where('session_status', 3)
            ->where('tutor_id', $tutor->id)
            ->get();
        if (!empty($scheduled_sessions)) {
            $scheduled_sessions_events = [];
            foreach ($scheduled_sessions as $session) {
                $child_name = $session->child->child_name ?? '';
                $event = [
                    'title' => $child_name . ' at ' . $session->session_time_ampm,
                    'start' => $session->session_date_ymd
                ];
                $scheduled_sessions_events[] = $event;
            }
            $this->scheduled_sessions_events = $scheduled_sessions_events;
        }

        
        $today = new \DateTime('now');
        $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
        $interval = $today->diff($birth);
        if ($interval->y < 18) $this->under_18 = true;

        $query = Job::where('hidden', 0)->where('job_status', 0)->orderBy('updated_at', 'DESC');
        if (!$tutor->online_acceptable_status) $query = $query->whereNot('session_type_id', 2);
        if ($this->under_18) $query = $query->whereNot('session_type_id', 1);

        $temp_jobs = $query->get();
        $jobs = [];
        $i = 0;
        if (!empty($temp_jobs)) {
            foreach ($temp_jobs as $job) {
                $ignored_tutors = $this->getIgnoredTutorsForJob($job->id);
                if (!empty($ignored_tutors) && in_array($tutor->id, $ignored_tutors)) continue;
                
                if (!!$job->vaccinated && $job->session_type_id == 1 && !$tutor->vaccinated) continue;

                if (empty($job->date)) continue;

                $job->create_time = \DateTime::createFromFormat('d/m/Y H:i', $job->create_time)->format('Y-m-d H:i');

                $child = $job->child;
                if (!empty($child)) {
                    $parent = $child->parent;
                    if ($parent->parent_state != $tutor->state) continue;

                    $rejected_jobs = RejectedJob::where('tutor_id', $tutor->id)->first();
                    if (!empty($rejected_jobs)) {
                        $rejected_exp = explode(',', $rejected_jobs->job_ids);
                        if (in_array($job->id, $rejected_exp)) continue;
                    }
                }
                $jobs[] = $job; 
                $i++;
                if ($i >= 2) break;
            }
        }
        $this->new_jobs = $jobs;

        $this->initTasks();

        $temp = [
            [
                'task_content' => 'http://www.lifehack.org/articles/communication/how-to-be-awesome-at-life.html',
                'task_name' => 'Be awesome!'
            ],
            [
                'task_content' => 'https://www.youtube.com/watch?v=9z5moxCaChY',
                'task_name' => 'Watch this cat video we found online'
            ],
            [
                'task_content' => 'http://thegreatestbooks.org/',
                'task_name' => 'Read more!'
            ],
            [
                'task_content' => 'https://www.realsimple.com/food-recipes/recipe-collections-favorites/healthy-meals/vegetarian-recipes#vegetarian-mushroom-gruyere-tart',
                'task_name' => 'Eat more vegetables.'
            ],
            [
                'task_content' => '/refer-friends',
                'task_name' => 'Refer your friends to tutor with us and get paid!'
            ],
            [
                'task_content' => 'https://www.flowersforeveryone.com.au/sydney-flowers/',
                'task_name' => 'Tell someone you love them.'
            ],
            [
                'task_content' => 'https://www.wikihow.com/Run',
                'task_name' => 'Go for a run.'
            ],
            [
                'task_content' => 'https://www.facebook.com/alchemytuition',
                'task_name' => 'Follow Alchemy Tuition on Facebook. '
            ],
            [
                'task_content' => 'https://www.edenseeds.com.au/',
                'task_name' => 'Plant a tree'
            ],
            [
                'task_content' => 'https://www.tasteofhome.com/collection/top-10-cake-recipes/view-all/',
                'task_name' => 'Learn to bake.'
            ],
        ];
        $this->random_tasks[] = $temp[rand(0, count($temp) - 1)];
        $this->random_tasks[] = $temp[rand(0, count($temp) - 1)];
    }

    public function initTasks()
    {
        $tutor = auth()->user()->tutor;
        $tasks = Task::where('tutor_id', $tutor->id)->where('task_completed', 0)
            ->where('task_hidden', 0)->where('task_subject', 'progress')->get();
        $this->tasks = $tasks;
    }

    public function taskHide($task_id)
    {
        try {
            Task::where('task_id', $task_id)->update(['task_hidden' => 1]);
            $this->initTasks();

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The task was hidden from the dashboard successfully."
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tutor.dashboard');
    }
}
