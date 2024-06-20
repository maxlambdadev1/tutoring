<?php

namespace App\Livewire\Admin\Parents;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ManualPayers extends Component
{
    public function render()
    {
        return view('livewire.admin.parents.manual-payers');
    }
}
