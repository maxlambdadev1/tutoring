<?php

namespace App\Livewire\Admin\Thirdparty;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ThirdpartyOrganisation;

#[Layout('admin.layouts.app')]
class CreateThirdpartyLead extends Component
{
    public $thirdparty_org_id;

    public function render()
    {
        $organisations = ThirdpartyOrganisation::get();
        
        return view('livewire.admin.thirdparty.create-thirdparty-lead', compact('organisations'));
    }
}
