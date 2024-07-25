<?php

namespace App\Livewire\Admin\Components;

use App\Models\Session;
use App\Trait\Functions;
use Livewire\Component;

class SessionSearchDetailInformation extends Component
{
    use Functions;

    public $session;

    public function mount($session_id) {
        $this->session = Session::find($session_id);
    }
    
    public function addComment($session_id, $comment)
    {
        if (!empty($session_id) && !empty($comment)) {
            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'session_id' => $session_id
            ]);

            $this->session = $this->session->fresh();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.session-search-detail-information');
    }
}
