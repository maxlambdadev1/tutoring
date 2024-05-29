<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class WaitingList extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.waiting-list');
    }
}
