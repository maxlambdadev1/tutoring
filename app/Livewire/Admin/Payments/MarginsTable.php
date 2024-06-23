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


class MarginsTable extends PowerGridComponent
{
    use Functions;

    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

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
            ->where(function ($query) {
                $query->where('session_status', 1)->orWhere('session_status', 3);
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
            ->add('session_price', fn ($ses) => $ses->session_price ?? '-')
            ->add('session_tutor_price', fn ($ses) => $ses->session_tutor_price ?? '-')
            ->add('margin', fn ($ses) => $ses->session_price - $ses->session_tutor_price ?? '-')
            ->add('total', function ($ses) {
                return Session::where('tutor_id', $ses->tutor_id)->where('child_id', $ses->child_id)
                    ->where(function ($query) {
                        $query->where('session_status', 2)->orWhere('session_status', 4);
                    })->count();
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Parent price(AUD)')->field('session_price')->sortable(),
            Column::add()->title('Tutor rate(AUD)')->field('session_tutor_price')->sortable(),
            Column::add()->title('Margin(AUD)')->field('margin'),
            Column::add()->title('Total of unconfirmed lessons')->field('total'),
        ];
    }
}
