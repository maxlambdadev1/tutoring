<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Option;
use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class PromoPage extends Component
{
    use Functions;
    public $option_value = '';
    public $promo_page_enabled = false;

    public function mount() {
        $this->option_value = $this->getOption('promo_page_content')  ?? '';
        $this->promo_page_enabled = $this->getOption('promo_page') ? true : false;
    }

    public function togglePromoPageEnabled() {
        try {
            if (!empty($this->option_value)) {
                Option::updateOrCreate([
                    'option_name' => 'promo_page'
                ], [
                    'option_value' => $this->promo_page_enabled 
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Saved successfully!'
                ]);
            } else throw new \Exception('Input data!');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function saveTemplate()
    {
        try {
            if (!empty($this->option_value)) {
                Option::updateOrCreate([
                    'option_name' => 'promo_page_content'
                ], [
                    'option_value' => $this->option_value
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Saved successfully!'
                ]);
            } else throw new \Exception('Input data!');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.promo-page');
    }
}
