<?php

namespace App\Livewire\Admin\Sessions;

use Illuminate\Support\Facades\DB;
use App\Models\CancellationFee;
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
use App\Trait\WithLeads;
use App\Trait\Automationable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;


class CancellationFeeTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public $status;
    public $thirdparty = false;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.cancellation-fee-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  CancellationFee::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_cancellation_fee.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_cancellation_fee.tutor_id', '=', 'tutors.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_cancellation_fee.child_id', '=', 'alchemy_children.id');
            });
            
        if ($this->thirdparty == true) $query = $query->whereNotNull('thirdparty_org_id');
        
        if ($this->status == 'pending') $query = $query->where('status', 0);
        else if ($this->status == 'approved') $query = $query->where('status', 1);
        else if ($this->status == 'declined') $query = $query->where('status', 2);

        return    $query->select('alchemy_cancellation_fee.*');

    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_first_name',
                'parent_last_name',
            ],
            'tutor' => [
                'tutor_name'
            ],
            'child' => [
                'child_name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('tutor_name', fn ($item) => $item->tutor->tutor_name)
            ->add('parent_first_name', fn ($item) => $item->parent->parent_first_name . ' ' . $item->parent->parent_last_name)
            ->add('child_name', fn ($item) => $item->child->child_name)
            ->add('reason', fn ($item) => $item->reason)
            ->add('session_date', fn ($item) => $item->session_date)
            ->add('date_submitted', fn ($item) => $item->date_submitted);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Reason')->field('reason'),
            Column::add()->title('Session date')->field('session_date'),
            Column::add()->title('Submitted on')->field('date_submitted'),
            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-info waves-effect waves-light btn-sm')
                ->toggleDetail(),
        ];
    }

    /** table actions */
    public function addComment($cancellation_id, $comment)
    {
        try {
            if (!empty($cancellation_id) && !empty($comment)) {
                $this->addCancellationFeeHistory([
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => $comment,
                    'cancellation_id' => $cancellation_id
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'The comment was saved successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function acceptCancellationFee($cancellation_id)
    {
        try {
            if (!empty($cancellation_id)) {
                $cancellation_fee = CancellationFee::find($cancellation_id);
                $cancellation_fee->update([
                    'status' => 1,
                    'date_last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
                ]);

                $params = [
                    'parentfirstname' => $cancellation_fee->parent_parent_first_name,
                    'sdate' => $cancellation_fee->session_date,
                    'cdate' => $cancellation_fee->date_submitted,
                    'reason' => $cancellation_fee->reason
                ];
                $this->sendEmail($cancellation_fee->parent->parent_email, 'parent-cancellation-fee', $params);

                $params = [
                    'tutorfirstname' => $cancellation_fee->tutor->first_name,
                    'username' => auth()->user()->admin->admin_name,
                ];
                $this->sendEmail($cancellation_fee->tutor->user->email, 'tutor-cancellation-fee-approve', $params);
                
                $this->addCancellationFeeHistory([
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Approved cancellation fee.',
                    'cancellation_id' => $cancellation_id
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Cancellation fee approved!'
                ]);
            } else throw new \Exception("Invalid parameters.");

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function rejectCancellationFee($cancellation_id)
    {
        try {
            if (!empty($cancellation_id)) {
                $cancellation_fee = CancellationFee::find($cancellation_id);
                $cancellation_fee->update([
                    'status' => 2,
                    'date_last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
                ]);

                $params = [
                    'tutorfirstname' => $cancellation_fee->tutor->first_name,
                    'username' => auth()->user()->admin->admin_name,
                ];
                $this->sendEmail($cancellation_fee->tutor->user->email, 'tutor-cancellation-fee-decline', $params);
                
                $this->addCancellationFeeHistory([
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Declined cancellation fee.',
                    'cancellation_id' => $cancellation_id
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Cancellation fee rejected!'
                ]);
            } else throw new \Exception("Invalid parameters.");

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
