<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Grade;
use App\Models\State;
use App\Models\Availability;
use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class BookLead extends Component
{
    public $who_you_are;
    public $parent_first_name;
    public $parent_last_name;
    public $parent_phone;
    public $parent_email;
    public $postcode;
    public $state_id;
    public $referral;
    public $student1 = [
        'student_first_name' => '',
        'student_last_name' => '',
        'grade_id' => '', //1 ~ 14
        'student_school' => '',
        'start_date' => '',  //ASAP or Date
        'start_date_picker' => '',  //dd/mm/YYYY
        'subject' => '',
        'availabilities' => [],
        'parent_looking' => '',
        'student_doing' => '',
        'additional_details' => '',
        'main_result' => '',
        'student_performance' => '',
        'student_attitude' => '',
        'student_mind' => '',
        'student_personality' => '',
        'student_favourite' => '',
        'prefered_gender' => '',
        'team_notes' => ''
    ];

    public $states;
    public $all_subjects;
    public $total_availabilities = [];
    public $grades = [];

    public function mount() {        
        $this->states = State::get();
        $this->all_subjects = Subject::get() ?? [];
        $this->total_availabilities = Availability::get();
        $this->grades = Grade::get();
    }

    public function render()
    {
        return view('livewire.tutor.pages.book-lead');
    }
}
