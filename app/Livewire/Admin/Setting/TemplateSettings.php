<?php

namespace App\Livewire\Admin\Setting;

use App\Models\Option;
use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class TemplateSettings extends Component
{
    use Functions;

    public $option_name;
    public $option_value;

    public function selectOption()
    {
        if (!empty($this->option_name)) {
            $this->option_value = ($this->getOption($this->option_name));
        } else $this->option_value = '';
    }

    public function saveTemplate()
    {
        try {
            if (!empty($this->option_name) && !empty($this->option_value)) {
                Option::updateOrCreate([
                    'option_name' => $this->option_name
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
        return view('livewire.admin.setting.template-settings');
    }
}
