<?php

namespace App\Livewire\Admin\Students;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class CurrentStudents extends Component
{
    public function render()
    {
        return view('livewire.admin.students.current-students');
    }
}
