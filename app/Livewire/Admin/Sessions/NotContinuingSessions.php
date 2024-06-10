<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class NotContinuingSessions extends Component
{
    public function render()
    {
        return view('livewire.admin.sessions.not-continuing-sessions');
    }
}
