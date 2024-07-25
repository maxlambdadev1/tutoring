<?php

namespace App\Livewire\Admin\Components;

use App\Models\Tutor;
use App\Models\Session;
use Livewire\Component;

class TutorSearchDetail extends Component
{
    public $tutor;

    public function mount($tutor_id) {
        $this->tutor = Tutor::find($tutor_id);
    }
    
    public function render()
    {
        return view('livewire.admin.components.tutor-search-detail');
    }
}
