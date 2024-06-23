<?php

namespace App\Livewire\Admin\Payments;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
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


class FailedPaymentsTable extends PowerGridComponent
{
    use Functions;

    public string $sortField = 'parent_first_name';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.failed-payment-detail')
                ->showCollapseIcon()
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
            ->where(function ($query1) {
                $query1->whereNull('session_charge_id')->orWhere('session_charge_id', '');
            })
            ->where(function ($query2) {
                $query2->where('session_charge_status', 'Payment failed')->orWhere(function ($query21) {
                    $query21->whereNotNull('session_parent_charge_status')->where('session_parent_charge_status', '!=', 'Paid');
                });
            });

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
            ->add('session_last_changed', fn ($ses) =>  $ses->session_last_changed ?? '-');
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
            Column::add()->title('Amount(AUD)')->field('session_amount'),
            Column::add()->title('Last attempted')->field('session_last_changed'),
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
    
    public function addComment($ses_id, $comment) {
        if (!empty($ses_id) && !empty($comment)) {
            $this->addFailedPaymentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'session_id' => $ses_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }
}
