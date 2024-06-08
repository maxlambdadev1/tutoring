<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ProgressReport extends Component
{
    public function render()
    {
        return view('livewire.admin.sessions.progress-report');
    }
}
