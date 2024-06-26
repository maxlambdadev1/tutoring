<?php

namespace App\Livewire\Admin\Reports;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\PostcodeDbArea;
use Illuminate\Contracts\Database\Query\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;


class ConversionReportTable extends PowerGridComponent
{
    use Functions;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

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
        $query =  Job::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_jobs.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_jobs.child_id', '=', 'alchemy_children.id');
            })
            ->whereNot('job_status', 0);

        return $query->select('alchemy_jobs.*');
    }

    public function fields(): PowerGridFields
    {

        return PowerGrid::fields()
            ->add('child_name', fn ($item) =>  $item->child->child_name ?? '-')
            ->add('create_time', fn ($item) =>  $item->create_time ?? '-')
            ->add('accepted_on', function ($item) {
                if ($item->job_status == 1) return $item->accepted_on ?? '-';
                else return 'DELETED - ' . $item->last_updated . ' : ' . $item->reason;
            })
            ->add('length', function ($item) {
                $create_date = \DateTime::createFromFormat('d/m/Y H:i', $item->create_time);
                if (!empty($create_date)) {
                    $cur_date = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
                    return $this->formatSeconds($cur_date->getTimestamp() - $create_date->getTimestamp());
                }
                else return '-';
            })
            ->add('subject', fn ($item) =>  $item->subject ?? '-')
            ->add('child_year', fn ($item) =>  $item->child->child_year ?? '-')
            ->add('location', fn ($item) =>  $item->location ?? '-')
            ->add('postcode', function ($item) {
                $postcode_db_area = !empty($item->parent) ? PostcodeDbArea::where('postcode', $item->parent->parent_postcode)->first() : '';
                return $postcode_db_area['area'] ?? '-';
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Lead created')->field('create_time'),
            Column::add()->title('Lead converted')->field('accepted_on'),
            Column::add()->title('Length')->field('length'),
            Column::add()->title('Subject')->field('subject'),
            Column::add()->title('Grade')->field('child_year'),
            Column::add()->title('Suburb')->field('location'),
            Column::add()->title('Area')->field('postcode'),
        ];
    }
}
