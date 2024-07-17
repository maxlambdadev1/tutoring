<?php

namespace App\Livewire\Tutor\YourDetail;

use App\Models\TutorWwcc;
use App\Models\TutorWwccValidate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdateWwcc extends Component
{
    public $tutor;
    public $wwcc_application_number;
    public $wwcc_full_name;
    public $wwcc_number;
    public $wwcc_expiry;

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->wwcc_application_number = $tutor->wwcc_application_number ?? '';
        $this->wwcc_full_name = $tutor->wwcc_fullname ?? '';
        $this->wwcc_number = $tutor->wwcc_number ?? '';
        $this->wwcc_expiry = $tutor->wwcc_expiry ?? '';
    }
    
    public function updateWWCC() {
        try {
            if (!empty($this->wwcc_number)) $this->wwcc_application_number = '';

            $this->tutor->update([
                'wwcc_application_number' => $this->wwcc_application_number,
                'wwcc_fullname' => $this->wwcc_full_name,
                'wwcc_number' => $this->wwcc_number,
                'wwcc_expiry' => $this->wwcc_expiry,
                'wwcc_last_update' => date('d/m/Y')
            ]);

            if (!empty($this->wwcc_application_number)) {
                $wwcc_validate = TutorWwccValidate::where('tutor_id', $this->tutor->id)->first();
                if (!empty($wwcc_validate)) {
                    if (time() - $wwcc_validate->timestamp >= 3628800) throw new \Exception('Your WWCC application has expired.   Please add a valid WWCC.');
                } else TutorWwccValidate::create([
                    'tutor_id' => $this->tutor->id,
                    'timestamp' => time()
                ]);
                TutorWwcc::where('tutor_id', $this->tutor->id)->delete();
            }

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
        return view('livewire.tutor.your-detail.update-wwcc');
    }
}
