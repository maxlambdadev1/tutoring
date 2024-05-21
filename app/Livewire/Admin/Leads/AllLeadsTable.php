<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use App\Models\Job;
use App\Models\Session;
use Illuminate\Contracts\Database\Query\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
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

class AllLeadsTable extends PowerGridComponent
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
            ->view('livewire.admin.components.lead-detail')
            ->showCollapseIcon()
            
        ];
    }

    public function datasource(): ?Builder
    {
        return Job::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_jobs.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_jobs.child_id', '=', 'alchemy_children.id');
            })
            ->select('alchemy_jobs.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_email',
                'parent_phone',
                'parent_first_name',
                'parent_last_name',
                'child_name',
                'parent_state',
                'parent_address',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('last_updated', function ($job) {
                $dtime = \DateTime::createFromFormat("d/m/Y H:i", $job->last_updated);
                $dtime->setTimeZone(new \DateTimeZone('Australia/Sydney'));
                $timestamp = $dtime->getTimestamp();
                $age = round((time() - $timestamp) / 3600, 0);
                return $age;
            })
            ->add('hidden', fn ($job) => $job->hidden ? 'Yes' : 'No')
            ->add('create_time')
            ->add('job_type', fn ($job) => $job->job_type == 'regular' ? '' : $job->job_type)
            ->add('session_type_id', fn ($job) => $job->session_type->name)
            ->add('parent_name', fn ($job) =>  $job->parent->parent_name)
            ->add('parent_phone', fn ($job) =>  $job->parent->parent_phone)
            ->add('parent_email', fn ($job) =>  $job->parent->parent_email)
            ->add('student_name', fn ($job) =>  $job->child->child_name)
            ->add('student_grade', fn ($job) =>  $job->child->child_year)
            ->add('subject', fn ($job) =>  $job->subject)
            ->add('state', fn ($job) =>  $job->parent->parent_state)
            ->add('address', fn ($job) =>  $job->parent->parent_address . " " . $job->location)
            ->add('progress_status', fn ($job) =>  $job->progress_status);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Age')->field('last_updated')->sortable(),
            Column::add()->title('Hidden')->field('hidden')->sortable(),
            Column::add()->title('Date submitted')->field('create_time')->sortable(),
            Column::add()->title('Lead Type')->field('job_type')->sortable(),
            Column::add()->title('Session Type')->field('session_type_id')->sortable(),
            Column::add()->title('Parent')->field('parent_name'),
            Column::add()->title('Parent Phone')->field('parent_phone')->searchable()->sortable(),
            Column::add()->title('Parent Email')->field('parent_email')->searchable()->sortable(),
            Column::add()->title('Student')->field('student_name', 'child_name')->sortable()->searchable(),
            Column::add()->title('Student Grade')->field('student_grade', 'child_year')->sortable(),
            Column::add()->title('Subject')->field('subject', 'subject')->sortable(),
            Column::add()->title('State')->field('state', 'parent_state')->sortable()->searchable(),
            Column::add()->title('Progress Status')->field('progress_status', 'progress_status')->sortable(),
            Column::action('Action'),
        ];
    }
    
    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-primary waves-effect waves-light')
                ->toggleDetail(),
        ];
    }
    
    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->setAttribute('class', 'bg-white'),
        ];
    }

}
