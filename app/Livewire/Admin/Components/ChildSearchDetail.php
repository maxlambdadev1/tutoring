<?php

namespace App\Livewire\Admin\Components;

use App\Models\Child;
use Livewire\Component;

class ChildSearchDetail extends Component
{
    public $child;

    public function mount($child_id) {
        $this->child = Child::find($child_id);
    }
    
    public function render()
    {
        return view('livewire.admin.components.child-search-detail');
    }
}
