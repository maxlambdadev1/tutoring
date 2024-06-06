<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class NoSession2Week extends Component
{
    public function render()
    {
        return view('livewire.admin.sessions.no-session2-week');
    }
}
