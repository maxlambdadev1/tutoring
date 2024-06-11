<?php

namespace App\Livewire\Admin\Components;

use App\Trait\Functions;
use Livewire\Component;
use App\Models\Tutor;
use App\Models\State;
use App\Models\TutorWwccValidate;

class EditTutorModal extends Component
{
    use Functions;

    public $tutor;
    public $tutor_email;
    public $tutor_phone;
    public $birthday;
    public $state;
    public $address;
    public $suburb;
    public $postcode;
    public $gender;
    public $expert_sub;
    public $ABN;
    public $bank_account_name;
    public $bank_account_number;
    public $bsb;
    public $wwcc_application_number;
    public $wwcc_fullname;    
    public $wwcc_expiry;
    public $wwcc_number;
    public $online_url;


    public function mount($tutor_id) {
        $tutor = Tutor::find($tutor_id);
        if (!empty($tutor)) {
            $this->tutor = $tutor;
            $this->tutor_email = $tutor->tutor_email;
            $this->tutor_phone = $tutor->tutor_phone;
            $this->birthday = $tutor->birthday;
            $this->state = $tutor->state;
            $this->address = $tutor->address;
            $this->suburb = $tutor->suburb;
            $this->postcode = $tutor->postcode;
            $this->gender = $tutor->gender;
            $this->ABN = $tutor->ABN;
            $this->bank_account_name = $tutor->bank_account_name;
            $this->bank_account_number = $tutor->bank_account_number;
            $this->bsb = $tutor->bsb;
            $this->wwcc_application_number = $tutor->wwcc_application_number;
            $this->wwcc_fullname = $tutor->wwcc_fullname;
            $this->wwcc_expiry = $tutor->wwcc_expiry;
            $this->wwcc_number = $tutor->wwcc_number;
            $this->online_url = $tutor->online_url;
        }
    }

    public function saveTutorDetails() {
        try {
            $address = str_replace(' ', '+', $this->address) . '+' . str_replace(' ', '+', $this->suburb) . '+' . $this->state. '+Australia';
            $coords = $this->getCoord($address);
            $lat = $coords['lat'] ?? 0;
            $lon = $coords['lon'] ?? 0;
            $current_date = date('d/m/Y');

            $this->tutor->update([
                'ABN' => $this->ABN,
                'bank_account_name' => $this->bank_account_name,
                'bsb' => $this->bsb,
                'bank_account_number' => $this->bank_account_number,
                'wwcc_application_number' => $this->wwcc_application_number,
                'wwcc_fullname' => $this->wwcc_fullname,
                'wwcc_number' => $this->wwcc_number,
                'wwcc_expiry' => $this->wwcc_expiry,
                'tutor_phone' => $this->tutor_phone,
                'tutor_five_status' => 0,
                'state' => $this->state,
                'tutor_state' => $this->state,
                'address' => $this->address,
                'suburb' => $this->suburb,
                'postcode' => $this->postcode,
                'lat' => $lat,
                'lon' => $lon,
                'birthday' => $this->birthday,
                'gender' => $this->gender,
                'online_url' => $this->online_url,
                'last_updated' => $current_date,
            ]);

            if (!empty($this->wwcc_application_number)) {
                $validate = $this->tutor->wwcc_validate;
                if (!empty($validate)) {
                    if (time() - $validate->timestamp >= 3628800) {
                        $this->dispatch('showToastrMessage', [
                            'status' => 'error',
                            'message' => 'Your WWCC application has expired. Please add a valid WWCC.'
                        ]);
                        return false;
                    }
                } else {
                    TutorWwccValidate::create([
                        'tutor_id' => $this->tutor->id,
                        'timestamp' => time()
                    ]);
                }
                $this->tutor->wwcc->delete();
            }

            $this->addTutorHistory([
                'tutor_id' => $this->tutor->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Edited profile."
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Details saved!'
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
        $states = State::get();
        return view('livewire.admin.components.edit-tutor-modal', compact('states'));
    }
}
