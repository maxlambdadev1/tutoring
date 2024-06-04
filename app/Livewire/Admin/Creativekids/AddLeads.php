<?php

namespace App\Livewire\Admin\Creativekids;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\State;
use App\Models\Grade;
use App\Models\SessionType;

#[Layout('admin.layouts.app')]
class AddLeads extends Component
{

    public $inputData = array();

    public function resetInput() {
        $this->inputData = [
            'parent_first_name' => '',
            'parent_last_name' => '',
            'parent_phone' => '',
            'parent_email' => '',
            'session_type_id' => '', //1 or 2
            'parent_address' => '',
            'student_first_name' => '',
            'student_last_name' => '',
            'grade_id' => '',
            'date' => array(),
            'voucher_number' => '',
            'birthday' => ''
        ];
    }

    public function render()
    {
        $states = State::get();
        $session_types = SessionType::where('kind', 'normal')->get();
        $grades = Grade::get();
        return view('livewire.admin.creativekids.add-leads', compact('states', 'session_types', 'grades'));
    }
}
