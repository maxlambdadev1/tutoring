<?php

namespace App\Livewire\Admin\Students;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class PastStudents extends Component
{
    public function render()
    {
        return view('livewire.admin.students.past-students');
    }
}
