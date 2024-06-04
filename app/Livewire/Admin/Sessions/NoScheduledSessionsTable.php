<?php

namespace App\Livewire\Admin\Sessions;

use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Child;
use App\Models\AlchemyParent;
use App\Models\Job;
use Illuminate\Support\Collection;
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


class NoScheduledSessionsTable extends PowerGridComponent
{

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.session-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Collection
    {
        $result = [];
        $children = Child::where('child_status', 1)->get();
        foreach ($children as $child) {
            $jobs = Job::where('parent_id', $child->parent_id)->where('child_id', $child->id)->where('job_status', 1)->where('job_type', '!=', 'creative')->where('accepted_by', '!=', '')->whereNotNull('accepted_by')->get();
            if (!empty($jobs)) {
                foreach ($jobs as $job) {
                    $no_ses = Session::where('child_id', $child->id)->where('parent_id', $child->parent_id)->where('tutor_id', $job->accepted_by)->where('session_is_first', 0)->orderBy('id', 'desc')->first();
                    if (!empty($no_ses)) {

                    }
                    $result[] = $no_ses;
                }
            }
        }

        return collect($result);
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('child_name', fn ($ses) => $ses->child->child_name)
            ->add('tutor_name', fn ($ses) =>  $ses->tutor->tutor_name)
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('prev_session', fn ($ses) =>  !empty($ses->prev_session) ? $ses->prev_session->session_date : ($ses->session_is_first == 1 ? 'First session' : '-'))
            ->add('over_due', function ($ses) {
				$now = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
				$dtime = \DateTime::createFromFormat("d/m/Y H:i", $ses->session_date.' '.$ses->session_time, new \DateTimeZone('Australia/Sydney'));
                $seconds = $now->getTimestamp() - $dtime->getTimestamp();
                return floor($seconds/3600) . 'h ' . floor(($seconds - 3600 * floor($seconds/3600))/60) . 'm';
            })
            ->add('tutor_phone', fn ($ses) =>  $ses->tutor->tutor_phone);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Previous session')->field('prev_session'),
            Column::add()->title('Overdue')->field('over_due'),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
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

}
