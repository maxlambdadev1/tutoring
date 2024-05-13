<?php

namespace App\Livewire\Admin\Setting;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Availability;

#[Layout('admin.layouts.app')]
class Availabilities extends Component
{
    use WithPagination;

    public $name;
    public $paramAvailabilities = [];

    public function addItemToAvailabilities($item) {
        $this->paramAvailabilities[] = $item;
    }

    public function deleteItemFromAvailabilities($item) {
        $this->paramAvailabilities = array_filter($this->paramAvailabilities, function($value) use ($item) {
            return $value !== $item;
        });
    }

    public function resetParamAvailabilities() {
        $this->paramAvailabilities = [];
    }

    public function updateAvailabilities()
    {
        $this->validate([
            'name' => 'required',
            'paramAvailabilities' => 'required'
        ]);

        try {
            $availability = Availability::find($this->name);
            $availability->time = $this->paramAvailabilities;
            $availability->save();
            
            $this->name = "";
            $this->paramAvailabilities = [];
            return redirect()->back()->with('info', __('The availability has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {
        $availabilities = Availability::paginate(10);
        return view('livewire.admin.setting.availabilities', compact('availabilities'));
    }
}
