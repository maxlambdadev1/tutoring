<?php

namespace App\Livewire\Admin\Thirdparty;

// use Illuminate\Database\query\Builder;

use Illuminate\Support\Facades\DB;
use App\Models\ThirdpartyOrganisation;
use Illuminate\Contracts\Database\Query\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use App\Trait\WithLeads;
use App\Trait\Automationable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;

class ThirdpartyOrganisationsTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.thirdparty-org-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  ThirdpartyOrganisation::query();

        return $query->select('*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('organisation_name', fn ($org) => $org->organisation_name)
            ->add('primary_contact_first_name', fn ($org) => $org->primary_contact_first_name)
            ->add('primary_contact_last_name', fn ($org) => $org->primary_contact_last_name)
            ->add('primary_contact_role', fn ($org) => $org->primary_contact_role)
            ->add('primary_contact_phone', fn ($org) => $org->primary_contact_phone)
            ->add('primary_contact_email', fn ($org) => $org->primary_contact_email);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Organisation')->field('organisation_name')->searchable()->sortable(),
            Column::add()->title('Contact first name')->field('primary_contact_first_name')->searchable()->sortable(),
            Column::add()->title('Contact last name')->field('primary_contact_last_name')->searchable()->sortable(),
            Column::add()->title('Contact role')->field('primary_contact_role')->searchable()->sortable(),
            Column::add()->title('Contact phone')->field('primary_contact_phone')->searchable()->sortable(),
            Column::add()->title('Contact Email')->field('primary_contact_email')->searchable()->sortable(),
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

}
