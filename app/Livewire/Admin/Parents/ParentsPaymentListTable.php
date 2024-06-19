<?php

namespace App\Livewire\Admin\Parents;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\AlchemyParent;
use App\Models\Child;
use App\Models\Session;
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


class ParentsPaymentListTable extends PowerGridComponent
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
                ->view('livewire.admin.components.parent-payment-list-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query = AlchemyParent::query()
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('alchemy_parent.id', '=', 'alchemy_sessions.parent_id')
                    ->on(function ($query) {
                        $query->whereNot('session_status', 6);
                    });
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('alchemy_children as c')
                      ->whereRaw('c.parent_id = alchemy_parent.id')
                      ->where('c.child_status', 1);
            })
            ->where(function ($query) {
                $query->whereNull('stripe_customer_id')->orWhere('stripe_customer_id', '= ', '');
            })        
            ->having('total_sessions', '>=', 2)
            ->groupBy('alchemy_parent.id');
        
        return $query->select('alchemy_parent.*',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
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
            ->add('parent_postcode', fn ($item) =>  $item->parent_postcode ?? '-');
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

    public function sendCcEmail1($parent_id)
    {
        try {
            $parent = AlchemyParent::find($parent_id);
            $children = $parent->children;
            $child = null;
            foreach ($children as $item) {
                if ($item->child_status == 1) {
                    $sessions_count = Session::where('parent_id', $parent->id)->where('child_id', $item->id)->where('session_status', '!=', 6)->count();
                    if ($sessions_count >= 2) {
                        $child = $item; break;
                    }
                }
            }

            if (!empty($parent) && !empty($child)) {
                $this->sendCcEmail($parent->id, $child->id);
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Email sent!'
                ]);
            } else throw new \Exception('Incorrent child information');

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function sendCcSMS1($parent_id)
    {
        try {
            $parent = AlchemyParent::find($parent_id);
            $children = $parent->children;
            $child = null;
            foreach ($children as $item) {
                if ($item->child_status == 1) {
                    $sessions_count = Session::where('parent_id', $parent->id)->where('child_id', $item->id)->where('session_status', '!=', 6)->count();
                    if ($sessions_count >= 2) {
                        $child = $item; break;
                    }
                }
            }

            if (!empty($parent) && !empty($child)) {
                $this->sendCcSMS($parent->id, $child->id);
                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'SMS sent!'
                ]);
            } else throw new \Exception('Incorrent child information');

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
}
