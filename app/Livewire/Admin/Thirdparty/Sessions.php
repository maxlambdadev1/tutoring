<?php

namespace App\Livewire\Admin\Thirdparty;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\ThirdpartyOrganisation;

#[Layout('admin.layouts.app')]
class Sessions extends Component
{
    public $thirdparty_org_id;

    public function render()
    {
        $organisations = ThirdpartyOrganisation::get();

        return view('livewire.admin.thirdparty.sessions', compact('organisations'));
    }
}
