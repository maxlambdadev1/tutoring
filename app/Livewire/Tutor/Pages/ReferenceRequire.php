<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\TutorApplication;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ReferenceRequire extends Component
{
    public $application;
    public $number;
    public $column;
    public $tutor_application_reference_fname1;
    public $tutor_application_reference_lname1;
    public $tutor_application_reference_email1;
    public $tutor_application_reference_relationship1;
    public $tutor_application_reference_fname2;
    public $tutor_application_reference_lname2;
    public $tutor_application_reference_email2;
    public $tutor_application_reference_relationship2;

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $application_id = explode('=', $exp[1])[1] ?? '';
                    $number = explode('=', $exp[2])[1] ?? '';
                    $column = explode('=', $exp[3])[1] ?? '';
                    if (!empty($application_id) && !empty($number) && !empty($column)) {
                        $secret_origin = sha1($application_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $application = TutorApplication::find($application_id);
                            if (!empty($application)) {
                                $this->application = $application_id;
                                $this->number = $number;
                                $this->column = $column;
                                $flag = true;
                            }
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));

        // $this->application = TutorApplication::find(123);
        // $this->number = 1;
        // $this->column = 'reference2';
    }

    public function updateApplicationReference() {
        try {
            $application = $this->application;
            if ($this->column == 'no') $application->update([
                'tutor_fname1' => $this->tutor_application_reference_fname1,
                'tutor_lname1' => $this->tutor_application_reference_lname1,
                'tutor_email1' => $this->tutor_application_reference_email1,
                'tutor_relation1' => $this->tutor_application_reference_relationship1,
                'tutor_fname2' => $this->tutor_application_reference_fname2,
                'tutor_lname2' => $this->tutor_application_reference_lname2,
                'tutor_email2' => $this->tutor_application_reference_email2,
                'tutor_relation2' => $this->tutor_application_reference_relationship2,
                'reference_reminder_48h' => 0,
                'reference_reminder_96h' => 0,
                'reference_update' => (new \DateTime())->format('d/m/Y H:i'),
            ]);
            else if ($column = 'reference1')  $application->update([
                'tutor_fname1' => $this->tutor_application_reference_fname1,
                'tutor_lname1' => $this->tutor_application_reference_lname1,
                'tutor_email1' => $this->tutor_application_reference_email1,
                'tutor_relation1' => $this->tutor_application_reference_relationship1,
                'reference_reminder_48h' => 0,
                'reference_reminder_96h' => 0,
                'reference_update' => (new \DateTime())->format('d/m/Y H:i'),
            ]);
            else if ($column == 'reference2')  $application->update([
                'tutor_fname2' => $this->tutor_application_reference_fname1,
                'tutor_lname2' => $this->tutor_application_reference_lname1,
                'tutor_email2' => $this->tutor_application_reference_email1,
                'tutor_relation2' => $this->tutor_application_reference_relationship1,
                'reference_reminder_48h' => 0,
                'reference_reminder_96h' => 0,
                'reference_update' => (new \DateTime())->format('d/m/Y H:i'),
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

    public function render()
    {
        return view('livewire.tutor.pages.reference-require');
    }
}
