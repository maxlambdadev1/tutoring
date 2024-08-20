<?php

namespace App\Livewire\Tutor\Sessions;

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
use Jenssegers\Agent\Agent;


class PreviousSessionsTable extends PowerGridComponent
{
    public string $sortField = 'id';
    public string $sortDirection = 'desc';



    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
            ->view('livewire.tutor.components.previous-session-detail')
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
            ->where(function ($query) {
                $query->where('session_status', 2)->orWhere('session_status', 4);
            })
            ->where('tutors.user_id', auth()->user()->id);

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('session_date', fn ($ses) => $ses->session_date . ' ' . $ses->session_time)
            ->add('type_id', fn ($ses) =>  $ses->type_id ==  1 ? 'Face To Face' : 'Online')
            ->add('session_tutor_notes', fn ($ses) =>  $ses->session_tutor_notes ?? '-');
    }

    public function columns(): array
    {
        $is_phone = (new Agent())->isPhone();
        
        if (!$is_phone) $columns = [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date'),
            Column::add()->title('Lesson type')->field('type_id')->sortable(),
            Column::add()->title('Your notes')->field('session_tutor_notes')->sortable(),
        ]; 
        else $columns = [
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date'),
        ]; 

        return $columns;
    }

}
