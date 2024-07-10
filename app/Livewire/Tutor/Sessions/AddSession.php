<?php

namespace App\Livewire\Tutor\Sessions;

use Livewire\Component;

use Livewire\Attributes\Layout;
use App\Trait\WithLeads;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\Session;
use App\Models\State;
use App\Models\Subject;
use App\Trait\Sessionable;

#[Layout('tutor.layouts.app')]
class AddSession extends Component
{
    use WithLeads, Sessionable;

    public $tutor;
    public $child_id; //selected child id
    public $students = []; //chilren array
    public $prev_session;

    public function mount()
    {
        $this->tutor = auth()->user()->tutor ?? null;
        $sessions = Session::where('session_is_first', 0)->where(function ($query) {
            $query->where('session_next_session_id', '')->orWhereNull('session_next_session_id');
        })->where('tutor_id', $this->tutor->id)
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->groupBy('child_id')
            ->orderBy('child_name')
            ->get();
        if (count($sessions) > 0) {
            foreach ($sessions as $session) {
                $child = $session->child;
                if (!empty($child)) $this->students[] = $child;
            }
        } else $this->students = [];
    }

    public function getPreviousSession()
    {
        if (!empty($this->child_id)) {
            $this->prev_session = Session::where('tutor_id', $this->tutor->id)->where('child_id', $this->child_id)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            $this->prev_session = null;
        }
    }

    public function addSession1($session_date, $session_time)
    { 
        try {
            $post = [
                'type' => 'regular',
                'session_date' => $session_date,
                'session_time' => $session_time,
                'prev_session_id' => $this->prev_session->id,
                'tutor_id' => $this->tutor->id,
            ];
            $this->addSession($post);

            $this->reset_values();
            return redirect()->back()->with('info', __('The session has been added successfully!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function reset_values()
    {
        $this->child_id = null;
        $this->prev_session = null;
    }

    public function render()
    {
        return view('livewire.tutor.sessions.add-session');
    }
}
