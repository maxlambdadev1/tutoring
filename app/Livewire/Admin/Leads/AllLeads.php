<?php

namespace App\Livewire\Admin\Leads;

use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


#[Layout('admin.layouts.app')]
class AllLeads extends Component
{
    public function render()
    {
        return view('livewire.admin.leads.all-leads');
    }
}
