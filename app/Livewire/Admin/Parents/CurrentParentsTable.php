<?php

namespace App\Livewire\Admin\Parents;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\AlchemyParent;
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


class CurrentParentsTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors, Sessionable;

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
                ->view('livewire.admin.components.parent-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query = AlchemyParent::query()
            ->leftJoin('alchemy_price_parent', function($price) {
                $price->on('alchemy_parent.id', '=', 'alchemy_price_parent.parent_id');
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('alchemy_children as c')
                      ->whereRaw('c.parent_id = alchemy_parent.id')
                      ->where('c.child_status', 1);
            });
        
        return $query->select('alchemy_parent.*', 'alchemy_price_parent.f2f');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('parent_first_name', fn ($item) =>  $item->parent_first_name . ' ' . $item->parent_last_name)
            ->add('parent_email', fn ($item) =>  $item->parent_email ?? '-')
            ->add('parent_phone', fn ($item) =>  $item->parent_phone ?? '-')
            ->add('parent_address', fn ($item) =>  $item->parent_address ?? '-')
            ->add('parent_suburb', fn ($item) =>  $item->parent_suburb ?? '-')
            ->add('parent_postcode', fn ($item) =>  $item->parent_postcode ?? '-')
            ->add('f2f', fn ($item) =>  $item->f2f ?? '-')
            ->add('stripe_customer_id', fn ($item) =>  $item->stripe_customer_id ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Parent phone')->field('parent_phone')->sortable()->searchable(),
            Column::add()->title('Parent address')->field('parent_address')->sortable(),
            Column::add()->title('Parent suburb')->field('parent_suburb')->sortable(),
            Column::add()->title('Parent postcode')->field('parent_postcode')->sortable(),
            Column::add()->title('Parent price')->field('f2f')->sortable(),
            Column::add()->title('Stripe ID')->field('stripe_customer_id')->sortable(),
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

    /** table actions */
    public function addComment($parent_id, $comment)
    {
        try {
            $this->addParentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'parent_id' => $parent_id
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
    
}
