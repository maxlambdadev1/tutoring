<?php

namespace App\Livewire\Admin\Tutors;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class TutorApplication extends Component
{
    public $application_status = 1;
    
    public function render()
    {
        return view('livewire.admin.tutors.tutor-application');
    }
}
