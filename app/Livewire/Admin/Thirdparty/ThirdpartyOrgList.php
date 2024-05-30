<?php

namespace App\Livewire\Admin\Thirdparty;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class ThirdpartyOrgList extends Component
{
    
    public function render()
    {
        return view('livewire.admin.thirdparty.thirdparty-org-list');
    }
}
