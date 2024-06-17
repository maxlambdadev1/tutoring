<?php

namespace App\Livewire\Admin\Wwcc;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Trait\Functions;
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


class AuditWwccTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;
    public string $sortField = 'tutor_name';
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
        $query = Tutor::query()
            ->where('tutor_status', 1);

        return $query->select('tutors.*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($item) =>  $item->tutor_name ?? '-')
            ->add('tutor_email', fn ($item) =>  $item->tutor_email ?? '-')
            ->add('state', fn ($item) =>  $item->state ?? '-')
            ->add('wwcc_application_number', fn ($item) =>  $item->wwcc_application_number ?? '-')
            ->add('wwcc_fullname', fn ($item) =>  $item->wwcc_fullname ?? '-')
            ->add('wwcc_number', fn ($item) =>  $item->wwcc_number ?? '-')
            ->add('wwcc_expiry', fn ($item) =>  $item->wwcc_expiry ?? '-')
            ->add('verified_on', fn ($item) =>  $item->wwcc->verified_on ?? '-')
            ->add('verified_by', fn ($item) =>  $item->wwcc->verified_by ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('State')->field('state')->sortable(),
            Column::add()->title('WWCC Application')->field('wwcc_application_number')->sortable(),
            Column::add()->title('WWCC Fullname')->field('wwcc_fullname')->sortable(),
            Column::add()->title('WWCC number')->field('wwcc_number')->sortable(),
            Column::add()->title('WWCC Expiry')->field('wwcc_expiry')->sortable(),
            Column::add()->title('Last verified on')->field('verified_on'),
            Column::add()->title('Last verified by')->field('verified_by'),
            Column::action('Action'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('verify-now')
                ->slot('Verify now')
                ->class('btn btn-outline-primary waves-effect waves-light btn-sm')
                ->openModal('verify-now', ['tutor_id' => $row->id]),
        ];
    }
     
    public function actionRules(): array
    {
        return [
            Rule::button('verify-now')
                ->when(fn ($row) => empty($row->wwcc_number))
                ->setAttribute('class', 'd-none') 
        ];
    }

    #[On('openModal')]
    public function openModal(string $component, array $arguments)
    {      
        try {
            $tutor_id = $arguments['tutor_id'];  
            $this->verifyWWCC($tutor_id);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "WWCC verified!"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
