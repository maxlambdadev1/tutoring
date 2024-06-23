<?php

namespace App\Livewire\Admin\Payments;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use Illuminate\Contracts\Database\Query\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;


class ManualPaymentsTable extends PowerGridComponent
{
    use Functions;

    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

        ];
    }

    public function header(): array
    {
        return [
            Button::add('bulk-mark-as-paid')
                ->slot('Mark as paid')
                ->class('btn btn-outline-secondary waves-effect btn-sm')
                ->dispatch('bulkMarkAsPaid.' . $this->tableName, []),
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Session::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_sessions.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            })
            ->where('session_charge_status', 'Manual payment required')
            ->where('session_status', 2);

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_first_name',
                'parent_last_name',
                'parent_email'
            ],
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_email'
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('parent_first_name', fn ($ses) => !empty($ses->parent) ? $ses->parent->parent_first_name . ' ' . $ses->parent->parent_last_name : '-')
            ->add('parent_email', fn ($ses) =>  $ses->parent->parent_email ?? '-')
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('tutor_email', fn ($ses) =>  $ses->tutor->tutor_email ?? '-')
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('session_amount', fn ($ses) => ($ses->session_price * $ses->session_length) ?? '-')
            ->add('tutor_amount', fn ($ses) => ($ses->session_tutor_price * $ses->session_length) ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date'),
            Column::add()->title('Parent amount(AUD)')->field('session_amount'),
            Column::add()->title('Tutor amount(AUD)')->field('tutor_amount'),
        ];
    }


    #[On('bulkMarkAsPaid.{tableName}')]
    public function bulkMarkAsPaid(): void
    {
        $today = new \DateTime('now');
        if (!empty($this->checkboxValues)) {
            foreach ($this->checkboxValues as $id) {
                $session = Session::find($id);
                $session->update([
                    'session_charge_time' => $today->format('d/m/Y'),
                    'session_charge_status' => 'Paid'
                ]);
            }
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The lessons was marked as paid!'
            ]);
        }
    }
}
