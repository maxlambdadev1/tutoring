<?php

namespace App\Livewire\Tutor;

use App\Models\CancellationFee;
use App\Models\PriceTutor;
use App\Models\Session;
use App\Models\Job;
use App\Models\SessionProgressReport;
use App\Models\PriceTutorOffer;
use App\Trait\PriceCalculatable;
use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class YourStudents extends Component
{
    use PriceCalculatable, Functions;

    public $students = [];
    public $prev_students = [];
    public $tutor;

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->getCurrentStudents();

        $this->prev_students = PriceTutor::query()
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_price_tutor.child_id', '=', 'alchemy_children.id');
            })
            ->where('tutor_id', $tutor->id)
            ->where('child_status', 0)
            ->orderBy('child_name')
            ->get();

    }

    public function getCurrentStudents() {
        $tutor = $this->tutor;
        
        $students = [];

        $price_tutors = PriceTutor::query()
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_price_tutor.child_id', '=', 'alchemy_children.id');
            })
            ->where('tutor_id', $tutor->id)
            ->orderBy('child_name')
            ->get();

        if (!$price_tutors->isEmpty()) {
            foreach ($price_tutors as $price_tutor) {
                $parent_id = $price_tutor->parent_id;
                $child_id = $price_tutor->child_id;
                $parent = $price_tutor->parent;
                $child = $price_tutor->child;

                $check_not_continuing_sessions = Session::where('tutor_id', $tutor->id)
                    ->where('parent_id', $parent_id)->where('child_id', $child_id)
                    ->where('session_status', 5)->count();
                if ($check_not_continuing_sessions < 1 && !empty($child) && $child->child_status == 1) {
                    $child->parent = $parent;
                    $job = Job::where('child_id', $child_id)->where('parent_id', $parent_id)->where('accepted_by', $tutor->id)
                        ->where('job_status', 1)->orderBy('id', 'desc')->first();
                    $session_type_id = $job->session_type_id ?? 1;
                    if ($session_type_id == 1) {
                        $child->price = $this->calcTutorPrice($tutor->id, $parent_id, $child_id);
                        $child->online = false;
                    } else {
                        $child->price = $this->calcTutorPrice($tutor->id, $parent_id, $child_id, 2);
                        $child->online = true;
                    }

                    $total_sessions = Session::where('tutor_id', $tutor->id)->where('child_id', $child_id)
                        ->where('session_length', '>', 0)
                        ->where(function ($query) {
                            $query->where('session_status', 2)->orWhere('session_status', 4);
                        })->count();
                    $child->total_sessions = $total_sessions;
                    $child->next_increase = (10 * (intval($total_sessions/10)) + 10) - $total_sessions;

                    $child->first_session =  Session::where('tutor_id', $tutor->id)->where('child_id', $child_id)
                        ->where('session_is_first', 1)->first();                        
                    $child->next_session =  Session::where('tutor_id', $tutor->id)->where('child_id', $child_id)
                    ->where('session_status', 3)->first();
                    $child->last_session =  Session::where('tutor_id', $tutor->id)->where('child_id', $child_id)
                        ->orderBy('id', 'desc')->first();   

                    $child->price_tutor_offer = PriceTutorOffer::getInstance($tutor->id, $parent_id, $child_id);

                    $progress_goals = SessionProgressReport::where('tutor_id', $tutor->id)->where('child_id', $child_id)
                        ->where(function ($query) {
                            $query->whereNotNull('q1')->orWhere('q1', '!=', '');
                        })->orderBy('id', 'desc')->first();
                    if (!empty($progress_goals)) {
                        $goals = [
                            'q1' => $progress_goals->q1,
                            'q2' => $progress_goals->q2,
                            'q3' => $progress_goals->q3,
                            'q4' => $progress_goals->q4,
                        ];
                    } else {
                        $goals = [
                            'q1' => $child->first_session->session_first_question1 ?? '',
                            'q2' => $child->first_session->session_first_question2 ?? '',
                            'q3' => $child->first_session->session_first_question3 ?? '',
                            'q4' => $child->first_session->session_first_question4 ?? '',
                        ];
                    }
                    $child->goals = $goals;

                    $students[] = $child;
                }
            }
        }
        $this->students = $students; 
    }

    public function submitCancellationFee($session_id, $reason) {
        $this->getCurrentStudents();
        try {
            $session = Session::find($session_id);

            $cancellation_fee = CancellationFee::updateOrCreate([
               'session_id' => $session_id, 
               'tutor_id' => $this->tutor->id,
               'parent_id' => $session->parent_id,
               'child_id' => $session->child_id
            ], [
                'reason' => $reason,
                'session_date' => $session->session_date . ' ' . $session->session_time_ampm,
                'date_submitted' => date('d/m/Y H:i')
            ]);

            $this->addCancellationFeeHistory([
                'cancellation_id' => $cancellation_fee->id,
                'author' => $this->tutor->tutor_name . ' (tutor)',
                'comment' => 'Requested cancellation fee. Reason: ' . $reason
            ]);

            //send email from admin@alchemy.team to info@alchemytuition.com.au

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Your cancellation fee was submitted!'
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
        return view('livewire.tutor.your-students');
    }
}
