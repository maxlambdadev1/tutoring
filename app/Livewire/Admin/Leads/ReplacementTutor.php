<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ReplacementTutor extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.replacement-tutor');
    }
}
