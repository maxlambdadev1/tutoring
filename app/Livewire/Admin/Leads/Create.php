<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class Create extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.create');
    }
}
