<?php

namespace App\Livewire\Tutor\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class ScheduledSession extends Component
{
    public function render()
    {
        return view('livewire.tutor.sessions.scheduled-session');
    }
}
