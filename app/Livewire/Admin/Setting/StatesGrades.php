<?php

namespace App\Livewire\Admin\Setting;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;
use App\Models\State;


#[Layout('admin.layouts.app')]
class StatesGrades extends Component
{
    public $state_name;
    public $description;
    public $editStateName;
    public $editStateDesc;
    public $editStateId = null;

    /** for state management */
    public function createState()
    {
        $this->validate([
            'state_name' => 'required|max:255',
            'description' => 'nullable|max:255'
        ]);
        try {
            $state = new State();
            $state->name = $this->state_name;
            $state->description = $this->description;
            $state->save();

            $this->reset_state_values();
            return redirect()->back()->with('info', __('New state has been registered successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function openEditStateModal(State $state)
    {
        $this->editStateId = $state->id;
        $this->editStateName = $state->name;
        $this->editStateDesc = $state->description;
        
        $this->dispatch('openEditStateModal', ['state_id' => $state->id]);
    }

    public function updateState()
    {
        $this->validate([
            'editStateName' => 'required|max:255',
            'editStateDesc' => 'nullable|max:255'
        ]);
        try {
            $state = State::find($this->editStateId);
            $state->name = $this->editStateName;
            $state->description = $this->editStateDesc;
            $state->save();

            $this->reset_state_values();
            return redirect()->back()->with('info', __('The state has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteState(State $state)
    {
        try {
            $state->delete();

            return back()->with('info', __('The state has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
    public function reset_state_values()
    {
        $this->state_name = "";
        $this->description = "";
        $this->editStateName = "";
        $this->editStateDesc = "";
        $this->editStateId = null;
    }

    /** for grade */

    public $gradeName;
    public $editGradeName;
    public $editGradeId = null;

    /** for state management */
    public function createGrade()
    {
        $this->validate([
            'gradeName' => 'required|max:255',
        ]);
        try {
            $grade = new Grade();
            $grade->name = $this->gradeName;
            $grade->save();

            $this->reset_grade_values();
            return redirect()->back()->with('info', __('New grade has been registered successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function openEditGradeModal(Grade $grade)
    {
        $this->editGradeId = $grade->id;
        $this->editGradeName = $grade->name;

        $this->dispatch('openEditGradeModal', ['grade_id' => $grade->id]);
    }

    public function updateGrade()
    {
        $this->validate([
            'editGradeName' => 'required|max:255',
        ]);
        try {
            $grade = Grade::find($this->editGradeId);
            $grade->name = $this->editGradeName;
            $grade->save();

            $this->reset_grade_values();
            redirect()->back()->with('info', __('The state has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteGrade(Grade $grade)
    {
        try {
            $grade->delete();

            return back()->with('info', __('The grade has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
    public function reset_grade_values()
    {
        $this->gradeName = "";
        $this->editGradeName = "";
        $this->editStateId = null;
    }

    public function render()
    {
        $grades = Grade::get();
        $states = State::get();
        return view('livewire.admin.setting.states-grades', compact('grades', 'states'));
    }

}
