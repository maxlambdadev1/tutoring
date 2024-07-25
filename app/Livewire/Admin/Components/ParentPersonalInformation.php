<?php

namespace App\Livewire\Admin\Components;

use App\Models\AlchemyParent;
use App\Trait\Functions;
use Livewire\Component;

class ParentPersonalInformation extends Component
{
    use Functions;

    public $parent;

    public function mount($parent_id) {
        $this->parent = AlchemyParent::find($parent_id);
    }
    
    public function addComment($parent_id, $comment)
    {
        if (!empty($parent_id) && !empty($comment)) {
            $this->addParentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'parent_id' => $parent_id
            ]);

            $this->parent = $this->parent->fresh();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.parent-personal-information');
    }
}
