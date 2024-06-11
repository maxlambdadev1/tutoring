<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Trait\WithLeads;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\Session;
use App\Models\State;
use App\Models\Subject;
use App\Trait\Sessionable;

#[Layout('admin.layouts.app')]
class AddSession extends Component
{
    use WithLeads, Sessionable;

    public $type = 'first'; //first session
    public $session_type_id = 1; //f2f session
    public $tutor_str;
    public $tutor_id; //selected tutor
    public $searched_tutors = []; //tutor array
    public $child_str;
    public $child_id; //selected child id
    public $searched_children = []; //chilren array
    public $prev_session_id;
    public $prev_sessions = [];
    public $session_date;
    public $session_time;
    public $subject;
    public $subjects = []; //subject string array
    public $cc_name;
    public $cc_number;
    public $cc_expiry;    
    public $cc_cvc;

    public function searchTutorsByName()
    {
        $this->searched_tutors = $this->searchTutors($this->tutor_str, 10);
    }

    public function selectTutor($tutor_id)
    {
        $this->tutor_id = $tutor_id;
        $tutor = Tutor::find($tutor_id);
        $this->tutor_str = $tutor->tutor_name . '(' . $tutor->user->email . ')';
        $this->selectPrevSessionsAndSubjects();
    }
    public function searchChildrenByName()
    {
        $this->searched_children = $this->searchChildren($this->child_str, 10);
    }
    public function selectChild($child_id)
    {
        $this->child_id = $child_id;
        $child = Child::find($child_id);
        $this->child_str = $child->child_name . '(' . $child->parent->parent_first_name . ' ' . $child->parent->parent_last_name . ')';
        $this->selectPrevSessionsAndSubjects();
    }

    public function selectPrevSessionsAndSubjects()
    {
        if ($this->type !== 'first') {
            if (!empty($this->tutor_id) && !empty($this->child_id)) {
                $this->prev_sessions = Session::where('tutor_id', $this->tutor_id)->where('child_id', $this->child_id)->where('session_is_first', 0)->whereNull('session_next_session_id')->where(function ($query) {
                    $query->where('session_status', 2)->orWhere('session_status', 4);
                })->get();

                if (!empty($this->prev_sessions)) {
                    $this->prev_session_id = $this->prev_sessions[0];
                    $session = Session::find($this->prev_session_id);
                    $this->subjects = [$session->session_subject];
                    $this->subject = $session->session_subject->name;
                }
            } else {
                $this->prev_sessions = [];
                $this->prev_session_id = null;
                $this->subjects = [];
                $this->subject = "";
            }
        } else if (!empty($this->child_id)) {
            $this->prev_sessions = [];
            $this->prev_session_id = null;

            $child = Child::find($this->child_id);
            $parent = $child->parent;
            $state = State::where('name', $parent->parent_state)->first();
            $state_id = 1;
            if (!empty($state)) $state_id = $state->id;
            $this->subjects = Subject::where('state_id', $state_id)->get();
            $this->subject = $this->subjects[0]->name;
        }
    }

    public function addSession1() {
        try {
            $post = [
                'type' => $this->type,
                'session_type_id' => $this->session_type_id,
                'session_date' => $this->session_date,
                'session_time' => $this->session_time,
                'prev_session_id' => $this->prev_session_id,
                'child_id' => $this->child_id,
                'tutor_id' => $this->tutor_id,
                'subject' => $this->subject,
                'cc_name' => $this->cc_name,
                'cc_number' => $this->cc_number,
                'cc_expiry' => $this->cc_expiry,
                'cc_cvc' => $this->cc_cvc
            ];
            $this->addSession($post);
            
            $this->reset_values(); 
            return redirect()->back()->with('info', __('The session has been added successfully!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function reset_values() {
        $this->type = "first";
        $this->session_type_id = 1;
        $this->tutor_str = '';
        $this->tutor_id = null;
        $this->searched_tutors = [];
        $this->child_str = '';
        $this->child_id = null;
        $this->searched_children = [];
        $this-> subject = '';
        $this->subjects = [];
        $this->prev_session_id = null;
        $this->prev_sessions = [];
        $this->session_date = '';
        $this->session_time = '';
        $this->cc_name = '';
        $this->cc_number = '';
        $this->cc_expiry = '';
        $this->cc_cvc = '';
    }

    public function render()
    {
        return view('livewire.admin.sessions.add-session');
    }
}
