<?php

namespace App\Livewire\Admin\Creativekids;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AddLeads extends Component
{
    public function render()
    {
        return view('livewire.admin.creativekids.add-leads');
    }
}
