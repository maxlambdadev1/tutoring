<?php

namespace App\Livewire\Admin\Layout;

use App\Models\Admin;
use App\Livewire\Common\Actions\Logout;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Navigation extends Component
{
    public $admin;
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function mount()
    {
        $this->admin = auth()->user()->admin;
    }
    
    public function render()
    {
        return view('livewire.admin.layout.navigation');
    }
}; 
