<?php

namespace App\Livewire\Admin\Tutors;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class TutorFirstSession extends Component
{
    public function render()
    {
        return view('livewire.admin.tutors.tutor-first-session');
    }
}
