<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class Dashboard extends Component
{
    public $first_sessions_week;
    public $first_sessions_previous;
    public $scheduled_sessions_week;
    public $scheduled_sessions_total;
    public $total_sessions_week;
    public $total_sessions_previous;

    public function mount() {

    }
    
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
