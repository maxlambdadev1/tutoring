<?php

namespace App\Livewire\Admin\Components;

use App\Models\Child;
use App\Trait\Functions;
use Livewire\Component;

class ChildPersonalInformation extends Component
{
    use Functions;

    public $child;

    public function mount($child_id) {
        $this->child = Child::find($child_id);
    }
    
    public function addComment($child_id, $comment)
    {
        if (!empty($child_id) && !empty($comment)) {
            $this->addStudentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'child_id' => $child_id
            ]);

            $this->child = $this->child->fresh();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.child-personal-information');
    }
}
