<?php

namespace App\Livewire\Admin\Reports;

use Illuminate\Support\Facades\DB;
use App\Models\ReportDaily;
use Illuminate\Contracts\Database\Query\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;


class DailyReportTable extends PowerGridComponent
{

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public $type = "";

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
        $query =  ReportDaily::query();

        return $query->select('alchemy_report_daily.*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date', fn ($item) =>  $item->date ?? '-')
            ->add('day', fn ($item) =>  $item->day ?? '-')
            ->add('bookings', fn ($item) =>  $item->bookings ?? '-')
            ->add('conversions', fn ($item) =>  $item->conversions ?? '-')
            ->add('tutor_conversions', fn ($item) =>  $item->tutor_conversions ?? '-')
            ->add('team_conversions', fn ($item) =>  $item->team_conversions ?? '-')
            ->add('total_confirmed_sessions', fn ($item) =>  $item->total_confirmed_sessions ?? '-')
            ->add('confirmed_first_sessions', fn ($item) =>  $item->confirmed_first_sessions ?? '-')
            ->add('total_confirmed_hours', fn ($item) =>  $item->total_confirmed_hours ?? '-')
            ->add('leads_in_system', fn ($item) =>  $item->leads_in_system ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Date')->field('date'),
            Column::add()->title('Day')->field('day'),
            Column::add()->title('Bookings')->field('bookings'),
            Column::add()->title('Conversions')->field('conversions'),
            Column::add()->title('Tutor conversions')->field('tutor_conversions'),
            Column::add()->title('Team conversions')->field('team_conversions'),
            Column::add()->title('Total confirmed sessions')->field('total_confirmed_sessions'),
            Column::add()->title('confirmed first sessions')->field('confirmed_first_sessions'),
            Column::add()->title('Total confirmed hours')->field('total_confirmed_hours'),
            Column::add()->title('Leads in system')->field('leads_in_system'),
        ];
    }

}
