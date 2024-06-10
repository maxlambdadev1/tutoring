<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class DailyFirstSessions extends Component
{
    public function render()
    {
        return view('livewire.admin.sessions.daily-first-sessions');
    }
}
