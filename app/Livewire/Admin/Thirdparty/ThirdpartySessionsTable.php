<?php

namespace App\Livewire\Admin\Thirdparty;

// use Illuminate\Database\query\Builder;

use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\User;
use App\Models\AlchemyParent;
use App\Models\Child;
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


class ThirdpartySessionsTable extends PowerGridComponent
{
    public $thirdparty_org_id;

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
            ->leftJoin('tutors', function ($child) {
                $child->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            })
            ->where('session_status', '!=', '6')
            ->where('thirdparty_org_id', '=', $this->thirdparty_org_id);

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
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('session_status', function ($ses) {
                $str = '';
                if ($ses->session_status == 1) $str = 'Unconfirmed';
                else if ($ses->session_status == 2) $str = 'Confirmed';
                else if ($ses->session_status == 3) $str = 'Scheduled';
                else if (($ses->session_status == 4 || $ses->session_status == 5)) {
                    if ($ses->session_length > 0) $str = 'Confirmed';
                    else $str = 'Unconfirmed';
                }
                return $str;
            })
            ->add('tutor_id', fn ($ses) => $ses->tutor_id)
            ->add('parent_id', fn ($ses) => $ses->parent_id)
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('child_first_name', fn ($ses) => $ses->child->child_name)
            ->add('child_year', fn ($ses) =>  $ses->child->child_year)
            ->add('child_school', fn ($ses) =>  $ses->child->child_school)
            ->add('parent_first_name', fn ($ses) =>  $ses->parent->parent_first_name . ' ' . $ses->parent->parent_last_name)
            ->add('parent_email', fn ($ses) =>  $ses->parent->parent_email)
            ->add('parent_phone', fn ($ses) =>  $ses->parent->parent_phone);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Session status')->field('session_status')->sortable(),
            Column::add()->title('Tutor ID')->field('tutor_id')->sortable(),
            Column::add()->title('Parent ID')->field('parent_id')->sortable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Student name')->field('child_first_name')->sortable()->searchable(),
            Column::add()->title('Student Grade')->field('child_year'),
            Column::add()->title('Student school')->field('child_school')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->searchable()->sortable(),
            Column::add()->title('Parent email')->field('parent_email')->searchable()->sortable(),
            Column::add()->title('Parent phone')->field('parent_phone')->searchable()->sortable(),
        ];
    }

}
