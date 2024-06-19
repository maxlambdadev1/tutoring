<?php

namespace App\Livewire\Admin\Students;

use App\Models\AlchemyParent;
use App\Models\Child;
use App\Models\User;
use App\Trait\WithLeads;
use App\Models\Grade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class AddStudents extends Component
{
    use WithLeads;

    public $parent_type = 'existing'; 
    public $parent_str = "";
    public $selected_parent;
    public $searched_parents = [];
    public $parent_first_name;
    public $parent_last_name;
    public $parent_phone;
    public $parent_email;
    public $parent_address;
    public $parent_suburb;
    public $parent_postcode;
    public $child_first_name;
    public $child_last_name;
    public $child_school;
    public $child_year;


    public function searchParentsByName() {
        $this->searched_parents = $this->searchParents($this->parent_str, 10);
    }

    public function selectParent($parent_id) {
        $parent = AlchemyParent::find($parent_id);
        if (!empty($parent)) {
            $this->selected_parent = $parent;
            $this->parent_str = $parent->parent_first_name . ' ' . $parent->parent_last_name . '(' . $parent->parent_email . ')';
        }
    }

    public function render()
    {
        $grades = Grade::get();
        return view('livewire.admin.students.add-students', compact('grades'));
    }

    public function addStudent() {
        try {
            if ($this->parent_type == 'existing') {
                if (!empty($this->selected_parent)) $parent_id = $this->selected_parent->id;
                else throw new \Exception('There is no parent. Please select the parent.');
            } else {
                $user = User::where('email', $this->parent_email)->first();
                if (!empty($user)) throw new \Exception('The email already used');

                $user = User::create([
                    'email' => $this->parent_email,
                    'password' => bcrypt('password'),
                    'role' => 2
                ]); 
                
                $parent = AlchemyParent::create([
                    'parent_first_name' => $this->parent_first_name,
                    'parent_last_name' => $this->parent_last_name,
                    'parent_email' => $this->parent_email,
                    'parent_phone' => $this->parent_phone,
                    'parent_address' => $this->parent_address,
                    'parent_suburb' => $this->parent_suburb,
                    'parent_postcode' => $this->parent_postcode,
                    'user_id' => $user->id
                ]);
                $parent_id = $parent->id;
            }

            Child::create([
                'child_name' => $this->child_first_name . ' ' . $this->child_last_name,
                'child_first_name' => $this->child_first_name,
                'child_last_name' => $this->child_last_name,
                'child_school' => $this->child_school,
                'child_year' => $this->child_year,
                'parent_id' => $parent_id,
            ]);
            
            $this->reset_values(); 
            return redirect()->back()->with('info', __('The student was added!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function reset_values() {
        $this->parent_type = 'existing';
        $this->parent_str = '';
        $this->selected_parent = null;
        $this->searched_parents = [];
        $this->parent_first_name = '';
        $this->parent_last_name = '';
        $this->parent_email = '';
        $this->parent_phone = '';
        $this->parent_address = '';
        $this->parent_suburb = '';
        $this->parent_postcode = '';
        $this->child_first_name = '';
        $this->child_last_name = '';
        $this->child_school = '';
        $this->child_year = '';
    }
}
