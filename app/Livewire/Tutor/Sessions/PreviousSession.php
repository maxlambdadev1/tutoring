<?php

namespace App\Livewire\Tutor\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class PreviousSession extends Component
{
    public function render()
    {
        return view('livewire.tutor.sessions.previous-session');
    }
}
