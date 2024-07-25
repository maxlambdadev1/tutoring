<?php

namespace App\Livewire\Admin\Components;

use App\Models\AlchemyParent;
use Livewire\Component;

class ParentSearchDetail extends Component
{
    public $parent;

    public function mount($parent_id) {
        $this->parent = AlchemyParent::find($parent_id);
    }
    
    public function render()
    {
        return view('livewire.admin.components.parent-search-detail');
    }
}
