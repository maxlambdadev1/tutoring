<?php

namespace App\Livewire\Admin\Components;

use App\Trait\PriceCalculatable;
use Livewire\Component;
use App\Models\Child;
use App\Models\Job;
use App\Models\Tutor;
use App\Models\Session;
use App\Trait\Functions;

class ChangeSessionType extends Component
{
    use Functions, PriceCalculatable;

    public $child;
    public $jobs;
    public $selected_tutor_id;
    public $selected_job;


    public function mount($child_id)
    {
        $this->child = Child::find($child_id);
        $parent = $this->child->parent;
        if (!empty($parent)) {
            $this->jobs = Job::where('job_status', 1)->where('child_id', $child_id)->where('parent_id', $parent->id)->get();
        }
    }

    public function changeTutor()
    {
        if (!empty($this->selected_tutor_id)) $this->selected_job = Job::where('job_status', 1)->where('child_id', $this->child->id)->where('parent_id', $this->child->parent->id)->where('accepted_by', $this->selected_tutor_id)->first();
        else $this->selected_job = null;
    }
    /**
     * @param $session_type_id : 1 or 2
     */

    public function changeSessionType($session_type_id)
    {
        try {
            if (!empty($this->selected_job)) {
                $session_type_id = $session_type_id == 1 ? 1 : 2;

                $this->selected_job->update([
                    'session_type_id' => $session_type_id
                ]);
                $child = $this->child;
                $parent = $child->parent;
                $tutor = $this->selected_job->tutor;

                $session_price = $this->calcSessionPrice($parent->id, $session_type_id);
                $tutor_price = $this->calcTutorPrice($tutor->id, $parent->id, $child->id, $session_type_id);

                $last_session = Session::where('tutor_id', $tutor->id)->where('parent_id', $parent->id)->where('child_id', $child->id)->orderBy('id', 'desc')->first();
                if (!empty($last_session)) {
                    $last_session->update([
                        'session_price' => $session_price,
                        'session_tutor_price' => $tutor_price,
                    ]);
                    
                    $status = $session_type_id == 1 ? 'F2F' : 'Online';
                    $this->addStudentHistory([
                        'child_id' => $child->id,
                        'author' => auth()->user()->admin->admin_name,
                        'comment' => 'Payment rate changed to ' . $status
                    ]);
                    
                    $this->dispatch('showToastrMessage', [
                        'status' => 'success',
                        'message' => 'The session type was updated successfully!'
                    ]);
                } else throw new \Exception('There is no session for this job');
            } else throw new \Exception('There is no selected job');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.change-session-type');
    }
}
