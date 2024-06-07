<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Child;
use App\Trait\WithParents;

class MakeStudentInactiveModal extends Component
{
    use WithParents;

    public $child;

    public function mount($child_id) {
        $this->child = Child::find($child_id);
    }

    public function render()
    {
        return view('livewire.admin.components.make-student-inactive-modal');
    }

    public function makeStudentInactive1($child_id, $delete_student_reason, $followup, $disable_future_follow_up_reason) {
        try {
            $this->makeStudentInactive($child_id, $delete_student_reason, $followup, $disable_future_follow_up_reason);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The student is now inactive!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
