<?php

namespace App\Livewire\Admin\Wwcc;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AuditWwcc extends Component
{
    public function render()
    {
        return view('livewire.admin.wwcc.audit-wwcc');
    }
}
