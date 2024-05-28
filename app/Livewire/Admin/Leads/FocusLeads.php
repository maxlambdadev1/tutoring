<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class FocusLeads extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.focus-leads');
    }
}
