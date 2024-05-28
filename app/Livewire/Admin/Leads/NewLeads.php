<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class NewLeads extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.new-leads');
    }
}
