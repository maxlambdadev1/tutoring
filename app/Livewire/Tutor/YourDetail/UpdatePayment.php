<?php

namespace App\Livewire\Tutor\YourDetail;

use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdatePayment extends Component
{
    use WithFileUploads;

    public $tutor;
    public $abn;
    public $bsb;
    public $bank_account_name;
    public $bank_account_number;
    public $id_type = 1;
    public $photo_front;
    public $photo_back;

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->abn = $tutor->ABN ?? '';
        $this->bsb = $tutor->bsb ?? '';
        $this->bank_account_name = $tutor->bank_account_name ?? '';
        $this->bank_account_number = $tutor->bank_account_number ?? '';
    }
    
    public function updatePaymentInfo() {
        try {
            $this->tutor->update([
                'ABN' => $this->abn,
                'bsb' => $this->bsb,
                'bank_account_name' => $this->bank_account_name,
                'bank_account_number' => $this->bank_account_number,
                'payment_last_update' => date('d/m/Y')
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

    public function updateIDImage() {
        try { 
            $tutor = $this->tutor;
            $directory = "uploads/" . $tutor->tutor_email;
            $photo_url = "";
            if ($this->photo_front) { 
                $extension = $this->photo_front->getClientOriginalExtension();
                $image_name = $tutor->tutor_email . "_photo_id" . "." . $extension;
                $this->photo_front->storeAs(path: "public/" . $directory, name: $image_name); 
                $photo_url = $directory . "/" . $image_name;
                $tutor->update(['id_photo' => $photo_url]);
            }
            $photo_url = "";
            if ($this->photo_back) {
                $extension = $this->photo_back->getClientOriginalExtension();
                $image_name = $tutor->tutor_email . "_photo_id_back" . "." . $extension;
                $this->photo_back->storeAs(path: "public/" . $directory, name: $image_name); 
                $photo_url = $directory . "/" . $image_name;
                $tutor->update(['id_photo_back' => $photo_url]);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The ID image have updated successfully!'
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
        return view('livewire.tutor.your-detail.update-payment');
    }
}
