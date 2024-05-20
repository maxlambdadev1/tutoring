<?php

namespace App\Livewire\Admin\Setting;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;
use App\Models\State;
use App\Models\Subject;

#[Layout('admin.layouts.app')]
class Subjects extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $editSubjectId = null;
    public $name = "";
    public $state = "";
    public $selectedGrades = [];
    public $showSubjectModal = false;

    public function openCreateSubjectModal() {
        $this->resetValues();
        $this->showSubjectModal = true;
    }
    
    public function openEditSubjectModal(Subject $subject) {
        $this->editSubjectId = $subject->id;
        $this->name = $subject->name;
        $this->state = $subject->state_id;
        $this->selectedGrades = $subject->getGradesId();
        $this->showSubjectModal = true;
    }

    public function saveSubject() {
        $this->validate([
            'name'=> 'required|max:255',
            'state'=> 'required|integer',
            'selectedGrades'=> 'required|array',
        ]);

        try {
            if ($this->editSubjectId) { //update subject
                $subject = Subject::find($this->editSubjectId);
                $subject->name = $this->name;
                $subject->state_id = $this->state;
                $subject->grades = $this->makeGradesToJson($this->selectedGrades);
                $subject->save();
                
                $this->resetValues();
                return redirect()->back()->with('info', __('New subject has been registered successfully!'));
            } else { //register subject
                Subject::create([
                    'name' => $this->name,
                    'state_id'=> $this->state,
                    'grades'=> $this->makeGradesToJson($this->selectedGrades),
                ]);
                
                $this->resetValues();
                return redirect()->back()->with('info', __('The subject has been registered successfully!'));
            }
        }   catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function deleteSubject(Subject $subject) {
        try {
            $subject->delete();
            return redirect()->back()->with('info', __('The subject has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $states = State::get();
        $grades = Grade::get();
        $subjects = Subject::orderBy('state_id')->paginate(10);

        return view('livewire.admin.setting.subjects', compact('states', 'grades', 'subjects'));
    }

    public function resetSubjects() {
        try {
            Subject::truncate();
            return redirect()->back()->with('info', __('All subjects has been deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->resetValues();
            session()->flash('error', $e->getMessage());
        }
    }

    public function resetValues() {
        $this->editSubjectId = null;
        $this->name = "";
        $this->state = "";
        $this->selectedGrades = [];
        $this->showSubjectModal = false;
    }
    
    private function makeGradesToJson($grades)
    {
        $temp_grades = [];
        foreach ($grades as $val) {
            $temp_grade = [];
            $temp_grade['id'] = $val;
            $temp_grade['name'] = Grade::find($val)?->name;
            array_push($temp_grades, $temp_grade);
        }

        return $temp_grades;
    }

}
