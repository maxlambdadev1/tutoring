<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\TutorApplicationStatus;
use App\Models\State;
use App\Models\Subject;
use App\Models\TutorApplication;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ApplyTutorApplication extends Component
{
    use Mailable;

    public $tutor_application_first_name;
    public $tutor_application_last_name;
    public $tutor_application_email_address;
    public $tutor_application_phone_number;
    public $tutor_application_state;
    public $tutor_application_postal;
    public $tutor_application_suburb;
    public $tutor_application_source;
    public $tutor_application_referral_code;
    public $tutor_application_graduated_year;
    public $tutor_application_high_school_in_aus;
    public $tutor_application_school;
    public $tutor_application_atar;
    public $tutor_application_achievements;
    public $tutor_application_current_situation;
    public $tutor_application_current_university;
    public $tutor_application_current_degree;
    public $tutor_application_graduated_university;
    public $tutor_application_graduated_degree;
    public $tutor_application_future_university;
    public $tutor_application_future_degree;
    public $tutor_application_tutored_before;
    public $tutor_application_experience;
    public $tutor_application_good_tutor;
    public $tutor_application_subjects = [];
    public $tutor_application_car;
    public $tutor_application_car_easy;
    public $tutor_application_introduction;
    public $tutor_application_dinner;
    public $tutor_application_advice;
    public $tutor_application_reference_fname1;
    public $tutor_application_reference_lname1;
    public $tutor_application_reference_email1;
    public $tutor_application_reference_relationship1;
    public $tutor_application_reference_fname2;
    public $tutor_application_reference_lname2;
    public $tutor_application_reference_email2;
    public $tutor_application_reference_relationship2;
    public $wwcc_check;
    public $abn_check;
    


    public $states;
    public $all_subjects;

    public function mount() {
        $this->states = State::get();
        $this->all_subjects = Subject::get() ?? [];
    }

    public function submitTutorApplication() {
        try {
            DB::beginTransaction();
            if (empty($this->tutor_application_email_address)) throw new \Exception('Please input all data correctly.');

            $application = TutorApplication::create([
                'tutor_first_name' => $this->tutor_application_first_name ?? '',
                'tutor_last_name' => $this->tutor_application_last_name ?? '',
                'tutor_phone' => $this->tutor_application_phone_number ?? '',
                'tutor_email' => $this->tutor_application_email_address ?? '',
                'tutor_state' => $this->tutor_application_state ?? '',
                'tutor_suburb' => $this->tutor_application_suburb ?? '',
                'postcode' => $this->tutor_application_postal ?? '',
                'tutor_referral' => $this->tutor_application_referral_code ?? '',
                'tutor_application_source' => $this->tutor_application_source ?? '',
                'tutor_graduation_year' => $this->tutor_application_graduated_year ?? '',
                'tutor_highschool_aus' => $this->tutor_application_high_school_in_aus ?? '',
                'tutor_atar' => $this->tutor_application_atar ?? '',
                'tutor_school' => $this->tutor_application_school ?? '',
                'tutor_achievements' => $this->tutor_application_achievements ?? '',
                'tutor_current_situation' => $this->tutor_application_current_situation ?? '',
                'tutor_current_university' => $this->tutor_application_current_university ?? '',
                'tutor_graduated_university' => $this->tutor_application_graduated_university ?? '',
                'tutor_current_degree' => $this->tutor_application_current_degree ?? '',
                'tutor_graduated_degree' => $this->tutor_application_graduated_degree ?? '',
                'tutor_future_university' => $this->tutor_application_graduated_degree ?? '',
                'tutor_future_degree' => $this->tutor_application_future_university ?? '',
                'tutor_introduction' => $this->tutor_application_introduction ?? '',
                'tutor_dinner' => $this->tutor_application_dinner ?? '',
                'tutor_advice' => $this->tutor_application_advice ?? '',
                'tutor_subjects' => implode(',', $this->tutor_application_subjects) ?? '',
                'tutor_tutored_before' => $this->tutor_application_tutored_before ?? '',
                'tutor_good_tutor' => $this->tutor_application_good_tutor ?? '',
                'tutor_application_experience' => $this->tutor_application_experience ?? '',
                'tutor_application_car' => $this->tutor_application_car ?? '',
                'tutor_application_car_easy' => $this->tutor_application_car_easy ?? '',
                'tutor_fname1' => $this->tutor_application_reference_fname1 ?? '',
                'tutor_lname1' => $this->tutor_application_reference_lname1 ?? '',
                'tutor_email1' => $this->tutor_application_reference_email1 ?? '',
                'tutor_relation1' => $this->tutor_application_reference_relationship1 ?? '',
                'tutor_fname2' => $this->tutor_application_reference_fname2 ?? '',
                'tutor_lname2' => $this->tutor_application_reference_lname2 ?? '',
                'tutor_email2' => $this->tutor_application_reference_email2 ?? '',
                'tutor_relation2' => $this->tutor_application_reference_relationship2 ?? '',
                'date_submitted' => (new \DateTime('now'))->format('d/m/Y H:i'),
                'date_last_update' => (new \DateTime('now'))->format('d/m/Y H:i'),
                'reference_update' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $application_status = 1;
            if ($this->tutor_application_graduated_year < 2020) $application_status = 7;
            if ($this->tutor_application_high_school_in_aus != "Yes") {
                $application_status = 7;
            } else {
                if ($this->tutor_application_atar == '70-79' || $this->tutor_application_atar == '80-89' ||$this->tutor_application_atar == '90-95' ||$this->tutor_application_atar == '95+')
                    $application_status = 7;
            }

            TutorApplicationStatus::create([
                'application_id' => $application->id,
                'application_status' => $application_status,
                'date_last_update' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);
            
            DB::commit();

            $params = [
                'tutorfirstname' => $this->tutor_application_first_name,
                'email' => $this->tutor_application_email_address
            ];
            $this->sendEmail($this->tutor_application_email_address, 'tutor-application-submit', $params);
            if (!empty($this->tutor_application_reference_email1) && !empty($this->tutor_application_reference_email2)) {
                $secret = sha1($this->tutor_application_reference_email1 . env('SHARED_SECRET'));
                $params = [
                    'email' => $this->tutor_application_reference_email1,
                    'tutorname' => $this->tutor_application_first_name . ' ' . $this->tutor_application_last_name,
                    'tutorfirstname' => $this->tutor_application_first_name,
                    'referencefirstname' => $this->tutor_application_reference_fname1,
                    'link' => "https://" . env('TUTOR') . '/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $this->tutor_application_reference_email1 . '&application_id' . $application->id. '&reason=no'),
                    'reasonlink' => "https://" . env('TUTOR') . '/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $this->tutor_application_reference_email1 . '&application_id' . $application->id. '&reason=yes')
                ];
                $this->sendEmail($this->tutor_application_reference_email1, 'tutor-application-reference-email', $params);
                
                $secret = sha1($this->tutor_application_reference_email2 . env('SHARED_SECRET'));
                $params = [
                    'email' => $this->tutor_application_reference_email2,
                    'tutorname' => $this->tutor_application_first_name . ' ' . $this->tutor_application_last_name,
                    'tutorfirstname' => $this->tutor_application_first_name,
                    'referencefirstname' => $this->tutor_application_reference_fname2,
                    'link' => "https://" . env('TUTOR') . '/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $this->tutor_application_reference_email2 . '&application_id' . $application->id. '&reason=no'),
                    'reasonlink' => "https://" . env('TUTOR') . '/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $this->tutor_application_reference_email2 . '&application_id' . $application->id. '&reason=yes')
                ];
                $this->sendEmail($this->tutor_application_reference_email2, 'tutor-application-reference-email', $params);
            }

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.apply-tutor-application');
    }
}
