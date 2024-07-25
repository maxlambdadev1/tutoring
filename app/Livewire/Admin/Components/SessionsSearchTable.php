<?php

namespace App\Livewire\Admin\Components;

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


class SessionsSearchTable extends PowerGridComponent
{

    public $tutor_id = null;
    public $parent_id = null;
    public $child_id = null;
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public $thirdparty_org_id;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.session-search-detail')
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
            ->leftJoin('users', function ($user) {
                $user->on('tutors.user_id', '=', 'users.id');
            })
            ->where('session_status', '!=', '6');

        if (!empty($this->tutor_id)) $query = $query->where('alchemy_sessions.tutor_id', '=', $this->tutor_id);
        if (!empty($this->parent_id)) $query = $query->where('alchemy_sessions.parent_id', '=', $this->parent_id);
        if (!empty($this->child_id)) $query = $query->where('alchemy_sessions.child_id', '=', $this->child_id);

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_email',
                'parent_phone',
                'parent_first_name',
                'parent_last_name',
            ],
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_name',
                'tutor_phone',
                'users' => [
                    'email'
                ]
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('session_status', function ($ses) {
                $str = '';
                if ($ses->session_status == 1) $str = 'Unconfirmed';
                else if ($ses->session_status == 2) $str = 'Confirmed';
                else if ($ses->session_status == 3) $str = 'Scheduled';
                else {
                    if ($ses->session_length > 0) $str = 'Confirmed';
                    else $str = 'Canceled';
                }
                return $str;
            })
            ->add('tutor_id', fn ($ses) => $ses->tutor_id)
            ->add('tutor_name', fn ($ses) =>  $ses->tutor->tutor_name ?? '-')
            ->add('child_id', fn ($ses) => $ses->child_id ?? '-')
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('parent_id', fn ($ses) => $ses->parent_id ?? '-')
            ->add('parent_first_name', fn ($ses) =>  $ses->parent ? $ses->parent->parent_first_name . ' ' . $ses->parent->parent_last_name : '-')
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('session_charge_status', fn ($ses) =>  $ses->session_charge_status ?? '-')
            ->add('type_id', fn ($ses) =>  $ses->type_id =  1 ? 'Face To Face' : 'Online');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Session status')->field('session_status'),
            Column::add()->title('Tutor ID')->field('tutor_id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Student ID')->field('child_id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Parent ID')->field('parent_id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->searchable()->sortable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Confirmed on')->field('session_last_changed')->sortable(),
            Column::add()->title('Lesson type')->field('type_id')->sortable(),
        ];
    }
}
