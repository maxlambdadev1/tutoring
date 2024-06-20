<?php

namespace App\Livewire\Admin\Parents;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ParentCheckIn extends Component
{
    public $filter = 'active';
    
    public function render()
    {
        return view('livewire.admin.parents.parent-check-in');
    }
}
