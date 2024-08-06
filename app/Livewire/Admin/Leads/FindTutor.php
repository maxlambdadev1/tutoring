<?php

namespace App\Livewire\Admin\Leads;

use App\Models\State;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\Availability;
use App\Models\PostcodeDb;
use App\Trait\WithLeads;
use Livewire\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class FindTutor extends Component
{
    use WithLeads;
    
    protected $paginationTheme = 'bootstrap';

    public $state;
    public $subjects = [];
    public $suburb;
    public $gender;
    public $vaccinated = false;
    public $experienced = false;
    public $seeking_students = false;
    public $non_metro_tutors = false;
    public $availabilities = [];
    public $states;
    public $all_subjects = [];
    public $total_availabilities = [];
    public $tutors = [];
    public $search_input;
    public $coords;

    public function mount() {
        $this->states = State::get();
        $this->all_subjects = Subject::get() ?? [];
        $this->total_availabilities = Availability::get();
    }

    public function findTutors() {
        $this->search_input = [
            'state' => $this->state,
            'subjects' => $this->subjects,
            'suburb' => $this->suburb,
            'gender' => $this->gender,
            'vaccinated' => $this->vaccinated,
            'experienced' => $this->experienced,
            'seeking_students' => $this->seeking_students,
            'non_metro_tutors' => $this->non_metro_tutors,
            'availabilities' => $this->availabilities,
        ];
        $coords = [
            'lat' => -33.788837,
            'lon' => 151.2841562
        ];
        if (!empty($this->suburb)) {
            $row = PostcodeDb::where('postcode', $this->suburb)->orWhere('suburb', 'like', '%' . $this->suburb . '%')->first();
            if (!empty($row)) {
                $coords = [
                    'lat' => $row->lat,
                    'lon' => $row->lon
                ];
            }
        }

        $this->tutors = $this->findTutorQuery($this->search_input)
            ->select('id', 'lat', 'lon', 'tutor_name', 'tutor_email', 'tutor_phone', 'accept_job_status')
            ->get(); 
        $this->coords = $coords;

        return [
            'tutors' => $this->tutors,
            'coords' => $this->coords
        ];
    }

    public function render()
    {
        return view('livewire.admin.leads.find-tutor');
    }
}
