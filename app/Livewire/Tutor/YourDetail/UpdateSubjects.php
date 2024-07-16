<?php

namespace App\Livewire\Tutor\YourDetail;

use App\Models\State;
use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdateSubjects extends Component
{
    public $tutor;
    public $all_subjects = [];
    public $subjects = [];

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $state = $tutor->state;
        $state_id = State::where('name', $state)->first()->id;
        $this->all_subjects = Subject::where('state_id', $state_id)->pluck('name') ?? [];
        $this->subjects = explode(',', $tutor->expert_sub) ?? [];
    }

    public function changeSubject($subject) {
        if (in_array($subject, $this->subjects)) {
            // Correctly return the comparison result to actually filter the array
            $this->subjects = array_filter($this->subjects, function ($value) use ($subject) {
                return $value != $subject; // Return the negation of the comparison
            });
        } else {
            // Directly add the subject to $this->subjects, no need for an intermediate variable
            $this->subjects[] = $subject;
        }
    }

    public function updateSubjects() {
        try {
            $subject_str = implode(',', $this->subjects);
            $this->tutor->update([
                'expert_sub' => $subject_str,
                'subjects_last_update' => date('d/m/Y')
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Updated successfully'
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
        return view('livewire.tutor.your-detail.update-subjects');
    }
}
