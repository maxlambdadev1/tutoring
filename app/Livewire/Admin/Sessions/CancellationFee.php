<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class CancellationFee extends Component
{
    public $active_status = 'pending';

    public function changeStatus($status) {
        if ($this->active_status != $status) $this->active_status = $status;
    }
    
    public function render()
    {
        return view('livewire.admin.sessions.cancellation-fee');
    }
}
