<?php

namespace App\Livewire\Admin\Payments;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Margins extends Component
{
    public function render()
    {
        return view('livewire.admin.payments.margins');
    }
}
