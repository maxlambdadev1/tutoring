<?php

namespace App\Livewire\Admin\Setting;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\SessionType;


#[Layout('admin.layouts.app')]
class SessionTypes extends Component
{
    use WithPagination;

    public $name;
    public $session_price;
    public $tutor_price;
    public $increase_rate;
    public $showSessionTypeModal = false;
    public $editSessionTypeId;
    
    public function openCreateSessionTypeModal() {
        $this->resetValues();
        $this->showSessionTypeModal = true;
    }


    public function openEditSessionTypeModal(SessionType $session_type) {
        $this->editSessionTypeId = $session_type->id;
        $this->name = $session_type->name;
        $this->session_price = $session_type->session_price;
        $this->tutor_price = $session_type->tutor_price;
        $this->increase_rate = $session_type->increase_rate;
        $this->showSessionTypeModal = true;
    }

    public function saveSessionType() {
        $this->validate([
            'name'=> 'required|max:255',
            'session_price'=> 'required|numeric',
            'tutor_price'=> 'required|numeric',
            'increase_rate'=> 'required|numeric',
        ]);

        try {
            if ($this->editSessionTypeId) { //update subject
                $session_type = SessionType::find($this->editSessionTypeId);
                $session_type->name = $this->name;
                $session_type->session_price = $this->session_price;
                $session_type->tutor_price = $this->tutor_price;
                $session_type->increase_rate = $this->increase_rate;
                $session_type->save();
                
                $this->resetValues();
                return redirect()->back()->with('info', __('New session type has been registered successfully!'));
            } else { //register subject
                SessionType::create([
                    'name' => $this->name,
                    'session_price'=> $this->session_price,
                    'tutor_price'=> $this->tutor_price,
                    'increase_rate'=> $this->increase_rate,
                ]);
                
                $this->resetValues();
                return redirect()->back()->with('info', __('The session type has been registered successfully!'));
            }
        }   catch (\Exception $e) {
            DB::rollBack();
            $this->resetValues();
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteSessionType(SessionType $session_type) {
        try {
            $session_type->delete();
            return redirect()->back()->with('info', __('The session type has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $sessionTypes = SessionType::paginate(10);
        return view('livewire.admin.setting.session-types', compact('sessionTypes'));
    }
    
    public function resetValues() {
        $this->editSessionTypeId = null;
        $this->name = "";
        $this->session_price = "";
        $this->tutor_price = "";
        $this->increase_rate = "";
        $this->showSessionTypeModal = false;
    }


}
