<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\Availability;
use App\Models\Tutor;
use App\Models\User;
use App\Models\ReplacementTutor;
use Illuminate\Contracts\Database\Query\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Number;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use App\Trait\WithLeads;
use App\Trait\Automationable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;


class ReplacementTutorTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.replacement-tutor-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  ReplacementTutor::query()
            ->where('replacement_status', '!=', 5)
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_replacement_tutor.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_replacement_tutor.child_id', '=', 'alchemy_children.id');
            })
            ->leftJoin('tutors', function ($child) {
                $child->on('alchemy_replacement_tutor.tutor_id', '=', 'tutors.id');
            });

        return $query->select("alchemy_replacement_tutor.*");
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_email',
                'parent_phone',
                'parent_first_name',
                'parent_last_name',
                'child_name',
                'tutor_name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('replacement_status', function ($item) {
                if ($item->replacement_status == 1) return 'Awaiting on tutor';
                else if ($item->replacement_status == 2) return 'Awaiting on parent';
                else return 'Other';
            })
            ->add('student_name', fn ($item) => $item->child->child_name)
            ->add('parent_first_name', fn ($item) => $item->parent->parent_first_name . ' ' . $item->parent->parent_last_name)
            ->add('tutor_name', fn ($item) => $item->tutor->tutor_name)
            ->add('repalcement_tutor_name', function ($item) {
                if (!empty($item->replacement_tutor)) return $item->replacement_tutor->tutor_name;
                return '-';
            })
            ->add('date_added', fn ($item) =>  $item->date_added)
            ->add('last_modified', fn ($item) =>  $item->last_modified);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Status')->field('replacement_status')->sortable(),
            Column::add()->title('Student name')->field('student_name')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable(),
            Column::add()->title('Replacement tutor name')->field('repalcement_tutor_name'),
            Column::add()->title('Date added')->field('date_added')->searchable()->sortable(),
            Column::add()->title('Last modified')->field('last_modified')->searchable()->sortable(),
            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-sm btn-outline-primary waves-effect waves-light')
                ->toggleDetail(),
        ];
    }

    /** table actions */
    public function addComment($child_id, $comment)
    {
        try {
            if (!empty($child_id) && !empty($comment)) {
                $this->addStudentHistory([
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => $comment,
                    'child_id' => $child_id
                ]);
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'The comment was saved successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
