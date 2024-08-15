<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Grade;
use App\Models\State;
use App\Models\Availability;
use App\Models\Subject;
use App\Trait\WithLeads;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class BookLead extends Component
{
    use WithLeads;

    public $who_you_are;
    public $parent_first_name;
    public $parent_last_name;
    public $parent_phone;
    public $parent_email;
    public $postcode;
    public $state_id;
    public $referral;
    public $session_type_id;
    public $address;
    public $subjects = [];
    public $student1 = [
        'student_first_name' => '',
        'student_last_name' => '',
        'grade_id' => '', //1 ~ 14
        'student_school' => '',
        'start_date' => '',  //ASAP or Date
        'start_date_picker' => '',  //dd/mm/YYYY
        'subject' => '',
        'availabilities' => [],
        'main_result' => '',
        'student_performance' => '',
        'student_attitude' => '',
        'student_mind' => '',
        'student_personality' => '',
        'student_favourite' => '',
        'prefered_gender' => '',
        'notes' => '',
        'favourite_1' => '',
        'favourite_2' => '',
        'favourite_3' => '',
    ];
    public $student2 = [
        'student_first_name' => '',
        'student_last_name' => '',
        'grade_id' => '', //1 ~ 14
        'student_school' => '',
        'start_date' => '',  //ASAP or Date
        'start_date_picker' => '',  //dd/mm/YYYY
        'subject' => '',
        'availabilities' => [],
        'main_result' => '',
        'student_performance' => '',
        'student_attitude' => '',
        'student_mind' => '',
        'student_personality' => '',
        'student_favourite' => '',
        'prefered_gender' => '',
        'notes' => '',
        'favourite_1' => '',
        'favourite_2' => '',
        'favourite_3' => '',
    ];
    public $student3 = [
        'student_first_name' => '',
        'student_last_name' => '',
        'grade_id' => '', //1 ~ 14
        'student_school' => '',
        'start_date' => '',  //ASAP or Date
        'start_date_picker' => '',  //dd/mm/YYYY
        'subject' => '',
        'availabilities' => [],
        'main_result' => '',
        'student_performance' => '',
        'student_attitude' => '',
        'student_mind' => '',
        'student_personality' => '',
        'student_favourite' => '',
        'prefered_gender' => '',
        'notes' => '',
        'favourite_1' => '',
        'favourite_2' => '',
        'favourite_3' => '',
    ];

    public $states;
    public $all_subjects;
    public $total_availabilities = [];
    public $grades = [];

    public function mount()
    {
        $this->states = State::get();
        $this->all_subjects = Subject::get() ?? [];
        $this->total_availabilities = Availability::get();
        $this->grades = Grade::get();
    }

    public function submitParentBooking()
    {
        try {
            $this->validate([
                'parent_first_name'     => 'required|max:255',
                'parent_last_name'      => 'required|max:255',
                'parent_phone'          => 'required|numeric',
                'parent_email'          => 'required|email|max:255',
                'session_type_id'       => 'required|numeric',
                'state_id'              => 'required|numeric',
                'address'               => 'required_if:session_type_id,1',
                'postcode'              => 'required_if:session_type_id,1|digits_between:4,5',
                'student1.student_first_name'    => 'required|max:255',
                'student1.student_last_name'     => 'required|max:255',
                'student1.grade_id'              => 'required|digits_between:1,2',
                'student1.start_date'            => 'required',
                'student1.start_date_picker'     => 'required_if:student1.start_date,"DATE"',
                'student1.subject'               => 'required',
                'student1.availabilities'        => 'required',
            ]);

            $this->student1['grade'] = Grade::find($this->student1['grade_id'])->name ?? '';
            $this->student1['date'] = $this->orderAvailabilitiesAccordingToDay($this->student1['availabilities']);
            $this->student1['start_date'] = $this->student1['start_date'] == 'ASAP' ? 'ASAP' : $this->student1['start_date_picker'];
            $this->student1['student_favourite'] = $this->student1['favourite_1'] . ', ' . $this->student1['favourite_2'] . ', ' . $this->student1['favourite_3'];
            if (!empty($this->student2['student_first_name']) && !empty($this->student2['grade_id'])) {
                $this->student2['grade'] = Grade::find($this->student2['grade_id'])->name ?? '';
                $this->student2['date'] = $this->orderAvailabilitiesAccordingToDay($this->student2['availabilities']);
                $this->student2['start_date'] = $this->student2['start_date'] == 'ASAP' ? 'ASAP' : $this->student2['start_date_picker'];
                $this->student2['student_favourite'] = $this->student2['favourite_1'] . ', ' . $this->student2['favourite_2'] . ', ' . $this->student2['favourite_3'];
            }
            if (!empty($this->student3['student_first_name']) && !empty($this->student3['grade_id'])) {
                $this->student3['grade'] = Grade::find($this->student3['grade_id'])->name ?? '';
                $this->student3['date'] = $this->orderAvailabilitiesAccordingToDay($this->student3['availabilities']);
                $this->student3['start_date'] = $this->student3['start_date'] == 'ASAP' ? 'ASAP' : $this->student3['start_date_picker'];
                $this->student3['student_favourite'] = $this->student3['favourite_1'] . ', ' . $this->student3['favourite_2'] . ', ' . $this->student3['favourite_3'];
            }
            $inputData = [
                'parent_first_name' => $this->parent_first_name,
                'parent_last_name' => $this->parent_last_name,
                'parent_phone' => $this->parent_phone,
                'parent_email' => $this->parent_email,
                'state' => State::find($this->state_id)->first()->name ?? '',
                'address' => $this->address,
                'postcode' => $this->postcode,
                'session_type_id' => $this->session_type_id,
                'referral' => $this->referral,
                'students' => [
                    'student1' => $this->student1,
                    'student2' => $this->student2,
                    'student3' => $this->student3,
                ],
                'is_from_main' => true,
            ];

            $this->CreateJobFromData($inputData);

            $this->redirect(env('MAIN_SITE') . '/bookingsuccess');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.book-lead');
    }

}
