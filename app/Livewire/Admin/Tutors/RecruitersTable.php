<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Recruiter;
use App\Models\Tutor;
use App\Trait\Functions;
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


class RecruitersTable extends PowerGridComponent
{

    use Mailable, Functions;
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
                ->view('livewire.admin.components.recruiter-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Recruiter::query()
            ->where('status', '=', '1');

        return $query->select('*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('first_name', fn ($item) =>  $item->first_name . ' ' . $item->last_name ?? '-')
            ->add('phone', fn ($item) =>  $item->phone ?? '-')
            ->add('email', fn ($item) =>  $item->email ?? '-')
            ->add('suburb', fn ($item) =>  $item->suburb ?? '-')
            ->add('state', fn ($item) =>  $item->state ?? '-')
            ->add('ABN', fn ($item) =>  $item->ABN ?? '-')
            ->add('bsb', fn ($item) =>  $item->bsb ?? '-')
            ->add('bank_account_number', fn ($item) =>  $item->bank_account_number ?? '-')
            ->add('referral_key', fn ($item) =>  $item->referral_key ?? '-')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Name')->field('first_name')->sortable()->searchable(),
            Column::add()->title('Phone')->field('phone')->sortable()->searchable(),
            Column::add()->title('Email')->field('email')->sortable()->searchable(),
            Column::add()->title('Suburb')->field('suburb')->sortable(),
            Column::add()->title('State')->field('state')->sortable(),
            Column::add()->title('ABN held')->field('ABN'),
            Column::add()->title('BSB')->field('bsb'),
            Column::add()->title('Bank account number')->field('bank_account_number'),
            Column::add()->title('Referral key')->field('referral_key'),
            Column::add()->title('Date joined')->field('created_at')->sortable(),
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

    public function addComment($item_id, $comment)
    {
        if (!empty($item_id) && !empty($comment)) {
            $this->addRecruiterHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'recruiter_id' => $item_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function updateRecruiter($id, $phone, $suburb, $state, $ABN, $bsb, $bank_account_number, $postcode)
    {
        try {
            $recruiter = Recruiter::find($id);

            $recruiter->update([
                'phone' => $phone,
                'suburb' => $suburb,
                'state' => $state,
                'ABN' => $ABN,
                'bsb' => $bsb,
                'bank_account_number' => $bank_account_number,
                'postcode' => $postcode
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The recruiter was successfuly edited"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function makeRecruierInactive($id, $reason)
    {
        try {
            $recruiter = Recruiter::find($id);

            $recruiter->update(['status' => 0]);

            $this->addRecruiterHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Sent recruiter to inactive. Reason: " .$reason,
                'recruiter_id' => $id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The recruiter is now inactive!"
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
