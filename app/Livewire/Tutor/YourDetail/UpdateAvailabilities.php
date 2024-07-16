<?php

namespace App\Livewire\Tutor\YourDetail;

use App\Models\Availability;
use App\Trait\Functions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class UpdateAvailabilities extends Component
{
    use Functions;

    public $tutor;
    public $availabilities = [];
    public $total_availabilities = [];
    public $is_selected_all = true;

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;
        $this->availabilities = $this->getAvailabilitiesFromString($tutor->availabilities) ?? [];
        if (empty($this->availabilities)) $this->is_selected_all = false;
        $this->total_availabilities = Availability::get();
    }

    public function toggleAvailItemStatus($avail_hour) {
        if (in_array($avail_hour, $this->availabilities)) {
            $this->availabilities = array_filter($this->availabilities, function ($value) use ($avail_hour) {
                return $value != $avail_hour; 
            });
        } else {
            $this->availabilities[] = $avail_hour;
        }
    }

    public function selectAllAvailabilities($flag) {
        if ($flag) {
            $this->availabilities = [];
            foreach ($this->total_availabilities as $item) {
                foreach ($item->getAvailabilitiesName() as $ele) {
                    $avail_hour = $item->short_name . '-' . $ele; //mon-7:00 AM
                    $this->availabilities[] = $avail_hour;
                }
            }
            $this->is_selected_all = true;
        } else {
            $this->availabilities = [];
            $this->is_selected_all = false;
        }
    }

    public function updateAvailabilities() {
        try {
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
