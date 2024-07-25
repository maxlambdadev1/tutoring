<?php

namespace App\Livewire\Admin\Components;

use App\Models\Availability;
use App\Trait\Functions;
use App\Models\Tutor;
use Livewire\Component;

class TutorProfileInformation extends Component
{
    use Functions;
    
    public $tutor;
    public $availabilities = [];
    public $total_availabilities = [];

    public function mount($tutor_id) {
        $tutor = Tutor::find($tutor_id);
        $this->tutor = $tutor;
        $this->availabilities = $this->getAvailabilitiesFromString($tutor->availabilities) ?? [];
        $this->total_availabilities = Availability::get();
    }

    public function render()
    {
        return view('livewire.admin.components.tutor-profile-information');
    }
}
