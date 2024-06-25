<?php

namespace App\Livewire\Admin\EndOfHoliday;

use App\Models\HolidayReachParent;
use App\Trait\Mailable;
use App\Models\HolidayStudent;
use App\Models\HolidayStudentHistory;
use Illuminate\Support\Facades\DB;
use App\Models\HolidayTutor;
use App\Models\PriceTutor;
use App\Models\ReplacementTutor;
use App\Models\Tutor;
use App\Models\Session;
use App\Trait\Functions;
use App\Trait\WithEndOfHoliday;
use Illuminate\Contracts\Database\Query\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;


class NewYearStudentTable extends PowerGridComponent
{

    use Mailable, Functions, WithEndOfHoliday;
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public $type = "";

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.new-year-student-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $student_type = $this->type == 'not-scheduled' ? 3 : 4;

        $query =  HolidayStudent::query()
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('tutors.id', '=', 'alchemy_holiday_student.last_tutor');
            })
            ->leftJoin('alchemy_children', function($child) {
                $child->on('alchemy_children.id', '=', 'alchemy_holiday_student.child_id');
            })
            ->leftJoin('alchemy_parent', function($parent) {
                $parent->on('alchemy_children.parent_id', '=', 'alchemy_parent.id');
            })
            ->where('year', date('Y'))
            ->where('alchemy_holiday_student.status', $student_type);

        if ($this->type == 'not-scheduled') {
            $query = $query->whereNotExists( function($query1){
                $query1->select(DB::raw(1))
                      ->from('alchemy_sessions as s')
                      ->whereRaw('s.tutor_id = alchemy_holiday_student.last_tutor')
                      ->whereRaw('s.child_id = alchemy_holiday_student.child_id')
                      ->where('s.session_status', 3)
                      ->orderBy('s.id', 'desc');
            });
        }

        return $query->select('alchemy_holiday_student.*');
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name',
                'alchemy_parent' => [
                    'parent_email'
                ]
            ],
            'tutor' => [
                'tutor_name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('status', function ($item){
                return $this->type ?? '-';
            })
            ->add('child_name', fn ($item) =>  $item->child->child_name ?? '-')
            ->add('parent_first_name', fn ($item) =>  $item->child->parent ? $item->child->parent->parent_first_name . ' ' . $item->child->parent->parent_last_name : '-')
            ->add('parent_email', fn ($item) =>  $item->child->parent->parent_email ?? '-')
            ->add('tutor_name', fn ($item) =>  $item->tutor->tutor_name ?? '-')
            ->add('date_created', fn ($item) =>  $item->date_created ?? '-')
            ->add('date_last_modified', fn ($item) =>  $item->date_last_modified ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Status')->field('status')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Last tutor')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Date created')->field('date_created'),
            Column::add()->title('Last modified')->field('date_last_modified'),
            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-primary waves-effect waves-light btn-sm')
                ->toggleDetail(),
        ];
    }

    public function addComment($holiday_id, $comment)
    {
        if (!empty($holiday_id) && !empty($comment)) {
            $this->addHolidayStudentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'holiday_id' => $holiday_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }
    
    /**
     * delete holiday student, history for holiday student.
     */
    public function removeHolidayStudentRecord($holiday_id) {
        try {
            $holiday_student = HolidayStudent::find($holiday_id);
            $holiday_student->delete();
            HolidayStudentHistory::where('holiday_id', $holiday_id)->delete();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The record is removed successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * move holiday tutor to replacement list
     */
    public function moveHolidayReplacement1($holiday_id) {
        try {
            $holiday_student = HolidayStudent::find($holiday_id);
            if (!empty($holiday_student)) {
                $tutor = $holiday_student->tutor;
                $child = $holiday_student->child;
                $this->moveHolidayReplacement($tutor->id, $child->id);

            } else throw new \Exception('There is no the holiday tutor.');
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The record is moved to replacement list successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * reach out to holiday parent
     */
    public function holidayReachParent($holiday_id) {
        try { 
            $holiday_student = HolidayStudent::find($holiday_id);
            if (!empty($holiday_student)) {
                $reach_parent = $holiday_student->reach_parent;
                if (!empty($reach_parent)) return $reach_parent->delete();

                $tutor = $holiday_student->tutor;
                $child = $holiday_student->child;
                $parent = $child->parent;                    
                $parent_ukey = base64_encode(serialize([
                    'type' => 'replacement-parent',
                    'replacement_id' => $holiday_id,
                    'tutor_id' => $tutor->id,
                    'tutor_name' => $tutor->tutor_name,
                    'child_name' => $child->child_name,
                    'child_first_name' => $child->first_name,
                    'parent_id' => $parent->id,
                    'child_id' => $child->id,
                    'parent_name' => $parent->parent_name
                ]));

                $reach_parent = HolidayReachParent::create([
                    'holiday_id' => $holiday_id,
                    'link' => $parent_ukey
                ]);

                $params = [
                    'email' => $parent->parent_email,
                    'parentfirstname' => $parent->parent_first_name,
                    'studentname' => $child->first_name,
                    'link' => 'https://alchemy.team/replacement-tutor?key=' . $parent_ukey,
                    'nolink' => 'https://alchemy.team/thankyou-parent?url=' . base64_encode('id=' . $holiday_id . '&type=temp')
                ];
                $this->sendEmail($parent->parent_email, 'end-of-holidays-new-year-student-1-email', $params);

                $this->addHolidayStudentHistory([
                    'holiday_id' => $holiday_id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Email-1 reach out sent to parent.'
                ]);
                
            } else throw new \Exception('There is no the holiday tutor.');
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The status is updated successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
