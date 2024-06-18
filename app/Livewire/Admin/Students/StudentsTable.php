<?php

namespace App\Livewire\Admin\Students;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Child;
use App\Trait\Functions;
use App\Trait\Sessionable;
use App\Trait\WithTutors;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Query\Builder;
use Livewire\Attributes\On;
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


class StudentsTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors, Sessionable;

    public $status = 1; //current students ? 1 : 0
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $followup_category_for_student = $this::FOLLOWUP_CATEGORY_FOR_STUDENT;

        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.student-detail')
                ->params(['followup_category_for_student' => $followup_category_for_student ])
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query = Child::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_parent.id', '=', 'alchemy_children.parent_id');
            })
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->groupBy('alchemy_children.id');
        
        if ($this->status == 1) $query = $query->where('child_status', 1);
        else $query = $query->where('child_status', 0);

        return $query->select('alchemy_children.*', 'alchemy_parent.parent_first_name',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('child_name', fn ($item) =>  $item->child_name ?? '-')
            ->add('google_ads')
            ->add('parent_id', fn ($item) =>  $item->parent_id ?? '-')
            ->add('parent_first_name', fn ($item) =>  !empty($item->parent) ? $item->parent->parent_first_name . ' ' . $item->parent->parent_last_name  : '-')
            ->add('parent_email', fn ($item) =>  $item->parent->parent_email ?? '-')
            ->add('parent_phone', fn ($item) =>  $item->parent->parent_phone ?? '-')
            ->add('total_sessions', fn ($item) =>  $item->total_sessions ?? '-')
            ->add('wwcc_fullname', fn ($item) =>  $item->wwcc_fullname ?? '-')
            ->add('wwcc_number', fn ($item) =>  $item->wwcc_number ?? '-')
            ->add('wwcc_expiry', fn ($item) =>  $item->wwcc_expiry ?? '-')
            ->add('graduation_year', fn ($item) =>  $item->graduation_year ?? '-');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_first_name',
                'parent_last_name',
                'parent_email'
            ],
        ];
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Google ads')->field('google_ads')->sortable()->toggleable(hasPermission: true, trueLabel: 'yes', falseLabel: 'no'),
            Column::add()->title('Parent Id')->field('parent_id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Parent phone')->field('parent_phone')->sortable()->searchable(),
            Column::add()->title('Total sessions')->field('total_sessions')->sortable(),
            Column::add()->title('Graduation year')->field('graduation_year')->sortable(),
            Column::action('Action'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-info waves-effect waves-light btn-sm')
                ->toggleDetail(),
        ];
    }

    public function onUpdatedToggleable($id, $field, $value): void
    {
        Child::query()->where('id', $id)->update([
            $field => $value,
        ]);
    }
    /** table actions */
    public function addComment($child_id, $comment)
    {
        try {
            $this->addStudentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'child_id' => $child_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateStudentDetail($child_id, $child_name, $child_school, $child_year)
    {
        try {
            $child = Child::find($child_id);
            $child->update([
                'child_name' => $child_name,
                'child_school' => $child_school,
                'child_year' => $child_year
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The student was successfuly edited.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function sendCcEmail1($child_id)
    {
        try {
            $child = Child::find($child_id);
            $parent = $child->parent;
            if (!empty($parent)) {
                $this->sendCcEmail($parent->id, $child_id);
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Email sent!'
                ]);
            } else throw new \Exception('Incorrent parent information');

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function makeStudentActive($child_id)
    {
        try {
            $child = Child::find($child_id);
            if (!empty($child)) {
                $child->update([
                    'child_status' => 1
                ]);

                $this->addStudentHistory([
                    'child_id' => $child->id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Sent student to active.'
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'The student is now active!'
                ]);
            } else throw new \Exception('There is no the student');

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
