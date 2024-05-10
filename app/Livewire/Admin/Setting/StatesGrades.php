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
    public $edit_state_name;
    public $edit_state_desc;
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
        $this->edit_state_name = $state->name;
        $this->edit_state_desc = $state->description;
        
        $this->dispatch('openEditStateModal', ['state_id' => $state->id]);
    }

    public function updateState()
    {
        $this->validate([
            'edit_state_name' => 'required|max:255',
            'edit_state_desc' => 'nullable|max:255'
        ]);
        try {
            $state = State::find($this->editStateId);
            $state->name = $this->edit_state_name;
            $state->description = $this->edit_state_desc;
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
        $this->edit_state_name = "";
        $this->edit_state_desc = "";
        $this->editStateId = null;
    }

    /** for grade */

    public $grade_name;
    public $edit_grade_name;
    public $editGradeId = null;

    /** for state management */
    public function createGrade()
    {
        $this->validate([
            'grade_name' => 'required|max:255',
        ]);
        try {
            $grade = new Grade();
            $grade->name = $this->grade_name;
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
        $this->edit_grade_name = $grade->name;

        $this->dispatch('openEditGradeModal', ['grade_id' => $grade->id]);
    }

    public function updateGrade()
    {
        $this->validate([
            'edit_grade_name' => 'required|max:255',
        ]);
        try {
            $grade = Grade::find($this->editGradeId);
            $grade->name = $this->edit_grade_name;
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
        $this->grade_name = "";
        $this->edit_grade_name = "";
        $this->editStateId = null;
    }

    public function render()
    {
        $grades = Grade::get();
        $states = State::get();
        return view('livewire.admin.setting.states-grades', compact('grades', 'states'));
    }

}
