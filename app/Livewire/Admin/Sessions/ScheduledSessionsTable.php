<?php

namespace App\Livewire\Admin\Sessions;

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


class ScheduledSessionsTable extends PowerGridComponent
{

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.session-detail')
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
            ->where('session_status', '=', '3');

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('child_name', fn ($ses) => $ses->child->child_name)
            ->add('tutor_name', fn ($ses) =>  $ses->tutor->tutor_name)
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('total_sessions', function ($ses) {
                return Session::where('tutor_id', $ses->tutor->id)->where('child_id', $ses->child->id)->count();
            })
            ->add('prev_session', fn ($ses) =>  !empty($ses->prev_session) ? $ses->prev_session->session_date : ($ses->session_is_first == 1 ? 'First session' : '-'));
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Total sessions')->field('total_sessions'),
            Column::add()->title('Previous session')->field('prev_session'),
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

}
