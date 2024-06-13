<?php

namespace App\Livewire\Admin\Tutors;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class TutorCheckIn extends Component
{    
    public $filter = 'active';

    public function render()
    {
        return view('livewire.admin.tutors.tutor-check-in');
    }
}
