<?php

namespace App\Livewire\Admin\Wwcc;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ChasingWwcc extends Component
{
    public function render()
    {
        return view('livewire.admin.wwcc.chasing-wwcc');
    }
}
