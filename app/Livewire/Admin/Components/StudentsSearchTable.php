<?php

namespace App\Livewire\Admin\Components;

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


class StudentsSearchTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors, Sessionable;

    public $tutor_id = null;
    public $child_id = null;    
    public $parent_id = null;     
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

            Detail::make()
                ->view('livewire.admin.components.student-search-detail')
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
            ->whereNotNull('alchemy_sessions.id')
            ->groupBy('alchemy_children.id')
            ->orderBy('child_name');    
        
        if (!empty($this->tutor_id)) $query = $query->where('alchemy_sessions.tutor_id', $this->tutor_id);
        if (!empty($this->parent_id)) $query = $query->where('alchemy_children.parent_id', $this->parent_id);
        if (!empty($this->child_id)) $query = $query->where('alchemy_children.id', $this->child_id);

        return $query->select('alchemy_children.*', 'alchemy_parent.parent_first_name',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('child_name', fn ($item) =>  $item->child_name ?? '-')
            ->add('parent_id', fn ($item) =>  $item->parent_id ?? '-')
            ->add('parent_first_name', fn ($item) =>  !empty($item->parent) ? $item->parent->parent_first_name . ' ' . $item->parent->parent_last_name  : '-');
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
            Column::add()->title('Parent Id')->field('parent_id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
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
}
