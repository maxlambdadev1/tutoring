<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\AlchemyParent;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class PaymentCc extends Component
{

    public $parent;
    public $card_name;
    public $card_number;
    public $expiry;
    public $cvc;

    public function mount()
    {
        $email = request()->query('email') ?? '';
        $email = str_replace(' ', '+', $email); 
        $flag = false;
        if (!empty($email)) {
            $this->parent = AlchemyParent::where('parent_email', $email)->first();
            if (!empty($this->parent)) $flag = true;
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
    }
    
    public function saveParentCc() {
        try {
            if (empty($this->card_name) || empty($this->card_number) || empty($this->expiry) || empty($this->cvc)) throw new \Exception("Please input all data correctly");

            //create stripe account and update parent.

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.pages.payment-cc');
    }
}
