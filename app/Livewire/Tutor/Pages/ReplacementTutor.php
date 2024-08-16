<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Job;
use App\Models\AlchemyParent;
use App\Models\Availability;
use App\Models\BookingTarget;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\Grade;
use App\Models\ReplacementTutor as ReplacementTutorModel;
use App\Models\HolidayReplacement;
use App\Models\HolidayStudent;
use App\Trait\Automationable;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\WithLeads;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ReplacementTutor extends Component
{
    use Functions, WithLeads, Automationable, Mailable;

    public $replacement_id;
    public $type;
    public $parent;
    public $child;
    public $tutor;

    public $availabilities = [];
    public $grade;
    public $student_notes;
    public $start_date;
    public $start_date_picker;
    public $session_type_id;
    public $address;
    public $total_availabilities = [];
    public $grades = [];
    public $session_date;
    public $notes;

    public function mount()
    {
        // $url = request()->query('key') ?? '';
        // $flag = false;
        // if (!empty($url)) {
        //     $details = unserialize(base64_decode($url));
        //     if (!empty($details)) {
        //         $replacement_id = $details['replacement_id'] ?? '';
        //         $type = $details['type'] ?? '';
        //         $tutor_id = $details['tutor_id'] ?? '';
        //         $parent_id = $details['parent_id'] ?? '';
        //         $child_id = $details['child_id'] ?? '';
        //         if (!empty($type) && !empty($replacement_id)) {
        // $this->type = $type;
        //             $this->parent = AlchemyParent::find($parent_id);
        //             $this->child = Child::find($child_id);
        //             $this->tutor = Tutor::find($tutor_id);
        //             if (!empty($this->parent) && !empty($this->child) && !empty($this->tutor)) $flag = true;
        //         }
        //     }
        // }
        // if (!$flag) $this->redirect(env('MAIN_SITE'));
        $this->parent = AlchemyParent::find(2889);
        $this->child = Child::find(3381);
        $this->tutor = Tutor::find(1062);
        $this->type = 'replacement-tutor';
        $this->replacement_id = 343;

        $this->availabilities = $this->job->availabilities ?? [];
        $this->total_availabilities = Availability::get();
        $this->grades = Grade::get();
    }

    public function render()
    {
        return view('livewire.tutor.pages.replacement-tutor');
    }

    public function replacementParent($address)
    {
        try {
            $type = $this->type;
            $this->address = $address;
            if (empty($this->grade)) throw new \Exception("Please select the grade");
            if (empty($this->session_type_id)) throw new \Exception("Please select the session type");
            if ($this->session_type_id == 1 && empty($this->address)) throw new \Exception("Please input the address");

            if ($type == 'replacement-parent') {
                $replacement = ReplacementTutorModel::where('tutor_id', $this->tutor->id)->where('parent_id', $this->parent->id)->where('child_id', $this->child->id)->where('id', $this->replacement_id)->where('replacement_status', 2)->first();
                if (empty($replacement))  throw new \Exception("No replacement found");
            } else if ($type == 'replacement-parent-temp') {
                $replacement = HolidayStudent::where('id', $this->replacement_id)->where('status', 4)->first();
                if (empty($replacement))  throw new \Exception("No holiday student found");
            }

            $old_job = Job::where('parent_id', $this->parent->id)->where('child_id', $this->child->id)->where('accepted_by', $this->tutor->id)->first();
            if (!empty($old_job)) {
                $location = $this->parent->parent_suburb;
                if ($this->session_type_id == 1) {
                    $coords = $this->getCoord($this->address);
                    $location = $coords['suburb'];
                    $this->parent->update([
                        'parent_address' => $coords['address'] ?? '',
                        'parent_suburb' => $coords['suburb'] ?? '',
                        'parent_lat' => $coords['lat'] ?? 0,
                        'parent_lon' => $coords['lon'] ?? 0,
                    ]);
                }

                $graduation_year = $this->getGraduationYear($this->grade);
                $this->child->update([
                    'child_year' => $this->grade,
                    'graduation_year' => $graduation_year,
                ]);

                if ($this->start_date != 'ASAP' && !empty($this->start_date_picker)) $start_date = $this->start_date_picker;
                else $start_date = 'ASAP';

                $date = $this->generateBookingAvailability($this->availabilities);
                $job = Job::create([
                    'job_type' => 'regular',
                    'progress_status' => 'New',
                    'parent_id' => $this->parent->id,
                    'child_id' => $this->child->id,
                    'date' => $date,
                    'start_date' => $start_date,
                    'subject' => $old_job->subject,
                    'location' => $location,
                    'prefered_gender' => $old_job->prefered_gender,
                    'job_notes' => $this->student_notes,
                    'main_result' => $old_job->main_result,
                    'performance' => $old_job->performance,
                    'attitude' => $old_job->attitude,
                    'mind' => $old_job->mind,
                    'personality' => $old_job->personality,
                    'favourite' => $old_job->favourite,
                    'job_status' => 0,
                    'session_type_id' => $this->session_type_id,
                    'hidden' => 0,
                    'source' => 'replacement',
                    'create_time' => date('d/m/Y H:i'),
                    'last_updated' => date('d/m/Y H:i'),
                    'vaccinated' => $old_job->vaccinated,
                    'experienced_tutor' => $old_job->experienced_tutor,
                    'automation' => 1,
                    'special_request_content' => $old_job->special_request_content,
                    'is_from_main' => $old_job->is_from_main,
                ]);

                if ($type == 'replacement-parent') {
                    $replacement->update([
                        'parent_notes' => $this->student_notes,
                        'last_modified' => (new \DateTime('now'))->format('d/m/Y H:i'),
                        'replacement_status' => 5,
                    ]);
                    HolidayReplacement::where('replacement_id', $this->replacement_id)->delete();
                } else if ($type == 'replacement-parent-temp') {
                    $replacement->update([
                        'status' => 5,
                    ]);
                }
                BookingTarget::create([
                    'job_id' => $job->id,
                    'source' => 'replacement',
                    'booking_date' => (new \DateTime('now'))->format('d/m/Y'),
                ]);

                $this->findTutorForJob($job->id);
            } else throw new \Exception("No old job found");

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function replacementExTutor()
    {
        try {
            if (empty($this->session_date) || empty($this->notes)) throw new \Exception("Please input all data");
            $replacement_tutor = ReplacementTutorModel::where('id', $this->replacement_id)->where('tutor_id', $this->tutor->id)->where('parent_id', $this->parent->id)->where('child_id', $this->child->id)->where('replacement_status', 1)->first();
            if (empty($replacement_tutor)) throw new \Exception("No replacement found.");

            $replacement_tutor->update([
                'replacement_status' => 2,
                'tutor_last_session' => $this->session_date,
                'tutor_notes' => $this->notes,
                'last_modified' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $replacementRowArray = unserialize(base64_decode($replacement_tutor->parent_link));
            $tutorfirstname = explode(' ', $replacementRowArray['tutor_name']);
            $studentfirstname = $this->child->first_name ?? '';
            $params = [
                'studentfirstname' => $studentfirstname,
                'tutorfirstname' => $tutorfirstname[0],
                'parentfirstname' => $this->parent->first_name ?? '',
                'lastsessiondate' => $this->session_date,
                'link' => $this->setRedirect("https://" . env('TUTOR') . "/replacement-tutor?key=" . $replacement_tutor->parent_link),
                'email' => $this->parent->parent_email,
            ];
            $this->sendEmail($params['email'], 'replacement-tutor-parent-email', $params);
            $this->addStudentHistory([
                'child_id' => $this->child->id,
                'author' => $replacementRowArray['tutor_name'],
                'comment' => $replacementRowArray['tutor_name'] . ' suggested ' . $this->session_date . ' as their last session date.',
            ]);


            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }
}
