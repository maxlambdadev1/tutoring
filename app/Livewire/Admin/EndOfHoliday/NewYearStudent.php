<?php

namespace App\Livewire\Admin\EndOfHoliday;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class NewYearStudent extends Component
{
    public function render()
    {
        return view('livewire.admin.end-of-holiday.new-year-student');
    }
}
