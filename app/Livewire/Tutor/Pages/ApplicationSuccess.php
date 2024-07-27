<?php

namespace App\Livewire\Tutor\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ApplicationSuccess extends Component
{
    public function render()
    {
        return view('livewire.tutor.pages.application-success');
    }
}
