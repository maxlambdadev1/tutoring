<?php

namespace App\Livewire\Tutor\YourDetail;

use App\Models\Availability;
use App\Trait\Functions;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdateAvailabilities extends Component
{
    use Functions, WithLeads;

    public $tutor;
    public $availabilities = [];
    public $total_availabilities = [];

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->availabilities = $this->getAvailabilitiesFromString($tutor->availabilities) ?? [];
        $this->total_availabilities = Availability::get();
    }

    public function updateAvailabilities($availabilities) {
        try {
            $this->availabilities = $this->orderAvailabilitiesAccordingToDay($availabilities);
            $av_str = $this->generateBookingAvailability($this->availabilities);
            $this->tutor->update([
                'availabilities' => $av_str
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
        return view('livewire.tutor.your-detail.update-availabilities');
    }
}
