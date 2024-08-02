<?php

namespace App\Livewire\Tutor\Jobs;

use App\Trait\Functions;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class JobsMap extends Component
{
    use Functions, WithLeads;
    
    public $jobs = [];
    public $tutor;
    public $coords;

    public function mount() {
        $tutor = auth()->user()->tutor;
        $this->tutor = $tutor;

        $this->jobs = $this->getAllJobs($tutor->id);
        $this->coords = [
            'lat' => $tutor->lat ?? 0,
            'lon' => $tutor->lon ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.tutor.jobs.jobs-map');
    }
}
