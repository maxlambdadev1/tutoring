<?php

namespace App\Livewire\Admin\Reports;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ConversionReport extends Component
{
    public function render()
    {
        return view('livewire.admin.reports.conversion-report');
    }
}
