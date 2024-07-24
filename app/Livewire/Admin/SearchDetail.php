<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class SearchDetail extends Component
{
    public $focus;
    public $id;
    
    public function mount()
    {
        $focus = Request::get('focus');
        $id = Request::get('id');        
        $this->focus = $focus ?? 'tutor';
        $this->id = $id ?? '';
    }

    public function render()
    {
        return view('livewire.admin.search-detail');
    }
}
