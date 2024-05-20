<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\State;
use App\Models\SessionType;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\Availability;
use App\Models\PriceParentDiscount;
use App\Trait\WithLeads;

#[Layout('admin.layouts.app')]
class Create extends Component
{
    use WithLeads;

    public $inputData = array();

    public function mount()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->inputData = [
            'parent_first_name' => '',
            'parent_last_name' => '',
            'parent_phone' => '',
            'parent_email' => '',
            'lead_source' => '',
            'session_type_id' => '', //1 or 2
            'state_id' => '', // 1 - 8
            'address' => '',
            'suburb' => '',
            'postcode' => '',
            'students' => [
                'student1' => [
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
                ]
            ],
            'referral' => '',
            'ignore_tutors' => [],  //tutor array
            'tutor_apply_offer' => false,
            'offer_type' => '', //fixed or percent
            'offer_amount' => '',
            'offer_valid' => '',
            'parent_apply_discount' => false,
            'discount_type' => '', //fixed or percent
            'discount_amount' => '',
            'hide_lead' => false,
            'automate' => false,
            'welcome_email' => true,
            'vaccinated' => false,
            'experienced_tutor' => false,
            'special_request_content' => '',
            'is_from_main' => false
        ];
        $this->search_str_for_tutors = "";
        $this->searched_tutors = [];
        $this->search_str_for_parent_child = "";
        $this->searched_parents_children = [];
        $this->current_step = 0;
        $this->subjects = [];
    }

    /**  extra */
    public $search_str_for_tutors = "";
    public $searched_tutors = []; //tutors
    public $search_str_for_parent_child = "";
    public $searched_parents_children = [];


    public $subjects; //according to state, grade_id
    public $current_step = 0; // 0 - 4

    public function searchTutorsForIgnore()
    {
        $this->searched_tutors = $this->searchTutors($this->search_str_for_tutors, 10);
    }

    public function searchParentsChildrenForInputing()
    {
        try {
            if (!empty($this->search_str_for_parent_child)) $this->searched_parents_children = $this->searchParentsChildren($this->search_str_for_parent_child, 10);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function selectParentAndChild($person)
    { //parent-child
        $this->resetInput(); 

        $this->inputData['parent_first_name'] = $person['parent_first_name'];
        $this->inputData['parent_last_name'] = $person['parent_last_name'];
        $this->inputData['parent_email'] = $person['parent_email'];
        $this->inputData['parent_phone'] = $person['parent_phone'];
        $this->inputData['address'] = $person['parent_address'];
        $this->inputData['suburb'] = $person['parent_suburb'];
        $this->inputData['postcode'] = $person['parent_postcode'];
        $discount = PriceParentDiscount::where('parent_id', $person['parent_id'])->first();
        if (!empty($discount)) {
            $this->inputData['discount_type'] = $discount->discount_type;
            $this->inputData['discount_amount'] = $discount->discount_amount;
        }
        $this->inputData['students']['student1']['student_first_name'] = $person['child_first_name'];
        $this->inputData['students']['student1']['student_last_name'] = $person['child_last_name'];
        $this->inputData['students']['student1']['student_school'] = $person['child_school'];
    }

    public function addTutorToIgnore(Tutor $tutor)
    {
        $added = false;
        foreach ($this->inputData['ignore_tutors'] as $ignore_tutor) {
            if ($ignore_tutor->id == $tutor->id) {
                $added = true;
            }
        }
        if (!$added) array_push($this->inputData['ignore_tutors'], $tutor);
    }

    public function removeTutorFromIgnore(Tutor $tutor)
    {
        $this->inputData['ignore_tutors'] = array_filter($this->inputData['ignore_tutors'], function ($value) use ($tutor) {
            return $value->id !== $tutor->id;
        });
    }

    public function getSubjectsFromStateAndGrade()
    {
        if (!empty($this->inputData['state_id']) && !empty($this->inputData['students']['student1']['grade_id'])) {
            $this->subjects = Subject::where('state_id', $this->inputData['state_id'])->whereJsonContains('grades', ['id' => $this->inputData['students']['student1']['grade_id']])->pluck('name');
        } else $this->subjects = null;
    }

    public function toggleAvailItemStatus($avail_hour)
    {
        if (in_array($avail_hour, $this->inputData['students']['student1']['availabilities'])) {
            $this->inputData['students']['student1']['availabilities'] = array_filter($this->inputData['students']['student1']['availabilities'], function ($value) use ($avail_hour) {
                return $value !== $avail_hour;
            });
        } else {
            array_push($this->inputData['students']['student1']['availabilities'], $avail_hour);
        }
    }

    public function finish()
    {
        try {
            $this->validate([
                'inputData.parent_first_name'     => 'required|max:255',
                'inputData.parent_last_name'      => 'required|max:255',
                'inputData.parent_phone'          => 'required|numeric',
                'inputData.parent_email'          => 'required|email|max:255',
                'inputData.session_type_id'       => 'required|numeric',
                'inputData.state_id'              => 'required|numeric',
                'inputData.address'               => 'required_if:inputData.session_type_id,1',
                'inputData.suburb'                => 'required_if:inputData.session_type_id,1',
                'inputData.postcode'              => 'required_if:inputData.session_type_id,1|digits_between:4,5',
                'inputData.students.student1.student_first_name'    => 'required|max:255',
                'inputData.students.student1.student_last_name'    => 'required|max:255',
                'inputData.students.student1.grade_id'              => 'required|digits_between:1,2',
                'inputData.students.student1.start_date'            => 'required',
                'inputData.students.student1.start_date_picker'     => 'required_if:inputData.students.student1.start_date,"DATE"',
                'inputData.students.student1.subject'               => 'required',
                'inputData.students.student1.availabilities'        => 'required',
                'inputData.offer_type'            => 'required_if:inputData.tutor_apply_offer, true',
                'inputData.offer_amount'          => 'required_if:inputData.tutor_apply_offer, true|numeric',
                'inputData.offer_valid'           => 'required_if:inputData.tutor_apply_offer, true',
                'inputData.discount_type'         => 'required_if:inputData.parent_apply_discount, true',
                'inputData.discount_amount'       => 'required_if:inputData.parent_apply_discount, true|numeric',
            ]);
            $inputData = $this->inputData;
            $inputData['state'] = State::find($inputData['state_id'])->first()->name;
            $inputData['address'] = $inputData['address'] . ", " . $inputData['suburb'] . ", " . $inputData['state'] . ' Australia';
            $inputData['students']['student1']['grade'] = Grade::find($inputData['students']['student1']['grade_id'])->first()->name;
            $inputData['students']['student1']['date'] = $inputData['students']['student1']['availabilities'];
            $inputData['students']['student1']['start_date'] = $inputData['students']['student1']['start_date'] == 'ASAP' ? 'ASAP' : $inputData['students']['student1']['start_date_picker'];
            $inputData['students']['student1']['notes'] = $inputData['students']['student1']['parent_looking'] . " \r\n" . $inputData['students']['student1']['student_doing'] . " \r\n" . $inputData['students']['student1']['additional_details'];
            $ignore_tutors = array();
            array_walk($this->inputData['ignore_tutors'], function ($value) use (&$ignore_tutors) {
                $ignore_tutors[] = $value->id;
            });
            $inputData['ignore_tutors'] = $ignore_tutors;

            $this->CreateJobFromData($inputData);
            $this->resetInput(); 
            return redirect()->back()->with('info', __('The job has been registered successfully!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $states = State::get();
        $session_types = SessionType::where('kind', 'normal')->get();
        $sources = $this::LEAD_SOURCE;
        $offer_valid_list = $this::VALID_UNTIL;
        $grades = Grade::get();
        $total_availabilities = Availability::get();
        return view('livewire.admin.leads.create', compact('states', 'session_types', 'sources', 'offer_valid_list', 'grades', 'total_availabilities'));
    }
}
