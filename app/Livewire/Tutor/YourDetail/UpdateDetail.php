<?php

namespace App\Livewire\Tutor\YourDetail;

use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdateDetail extends Component
{
    use Functions;

    public $tutor;
    public $first_name;
    public $last_name;
    public $tutor_email;
    public $tutor_phone;
    public $birthday;
    public $gender;
    public $address;
    public $suburb;
    public $postcode;

    public function mount() {
        $tutor = auth()->user()->tutor;

        $this->tutor = $tutor;
        $this->first_name = explode(' ', $tutor->tutor_name)[0] ?? '';
        $this->last_name = explode($this->first_name, $tutor->tutor_name)[1] ?? '';
        $this->tutor_email = $tutor->tutor_email ?? '';
        $this->tutor_phone = $tutor->tutor_phone ?? '';
        $this->birthday = $tutor->birthday ?? '';
        $this->gender = $tutor->gender ?? '';
        $this->address = $tutor->address ?? '';
        $this->suburb = $tutor->suburb ?? '';
        $this->postcode = $tutor->postcode ?? '';
    }

    public function updateDetail() {
        try {
            $address = str_replace(' ', '+', $this->address . '+'. $this->suburb . '+NSW+Australia');
            $coords = $this->getCoord($address);
            $this->tutor->update([
                'tutor_name' => ucwords($this->first_name . ' ' . $this->last_name),
                'tutor_phone' => $this->tutor_phone,
                'address' => $this->address,
                'suburb' => $this->suburb,
                'postcode' => $this->postcode,
                'lat' => $coords['lat'] ?? '',
                'lon' => $coords['lon'] ?? '',
                'birthday' => $this->birthday,
                'gender' => $this->gender,
                'personal_details_last_update' => date('d/m/Y')
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Updated successfully'
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
        return view('livewire.tutor.your-detail.update-detail');
    }
}
