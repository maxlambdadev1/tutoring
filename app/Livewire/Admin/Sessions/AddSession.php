<?php

namespace App\Livewire\Admin\Sessions;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AddSession extends Component
{
    public $type = 'first'; //first session
    public $session_type_id = 1; //f2f session
    public $tutor;
    public $student;
    public $session_date;
    public $session_time;
    public $subject;
    
    public function render()
    {
        return view('livewire.admin.sessions.add-session');
    }
}
