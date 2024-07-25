<?php

namespace App\Livewire\Admin\Components;

use App\Models\Tutor;
use App\Trait\Functions;
use Livewire\Component;

class TutorPersonalInformation extends Component
{
    use Functions;

    public $tutor;

    public function mount($tutor_id) {
        $this->tutor = Tutor::find($tutor_id);
    }
    
    public function addComment($tutor_id, $comment)
    {
        if (!empty($tutor_id) && !empty($comment)) {
            $this->addTutorHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'tutor_id' => $tutor_id
            ]);

            $this->tutor = $this->tutor->fresh();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.tutor-personal-information');
    }
}
