<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\Availability;
use App\Models\Tutor;
use App\Models\User;
use App\Models\WaitingLeadOffer;
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


class DeletedLeadsTable extends PowerGridComponent
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
                ->view('livewire.admin.components.deleted-lead-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Job::query()
            ->where('job_status', '=', 2)
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_jobs.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_jobs.child_id', '=', 'alchemy_children.id');
            });

        return $query->select("alchemy_jobs.*", DB::raw("DATE_FORMAT(alchemy_jobs.updated_at, '%d/%m/%Y %H:%i') AS deleted_date"));
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
            ->add('deleted_date', fn ($job) => $job->deleted_date)
            ->add('last_updated', fn ($job) => $job->last_updated)
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
            ->add('source', fn ($job) =>  $job->source);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Date Deleted')->field('deleted_date')->sortable(),
            Column::add()->title('Date submitted')->field('last_updated')->sortable(),
            Column::add()->title('Lead Type')->field('job_type')->sortable(),
            Column::add()->title('Session Type')->field('session_type_id')->sortable(),
            Column::add()->title('Parent')->field('parent_name'),
            Column::add()->title('Parent Phone')->field('parent_phone')->searchable()->sortable(),
            Column::add()->title('Parent Email')->field('parent_email')->searchable()->sortable(),
            Column::add()->title('Student')->field('student_name', 'child_name')->sortable()->searchable(),
            Column::add()->title('Grade')->field('student_grade', 'child_year')->sortable(),
            Column::add()->title('Subject')->field('subject', 'subject')->sortable(),
            Column::add()->title('State')->field('state', 'parent_state')->sortable()->searchable(),
            Column::add()->title('Source')->field('source', 'source')->sortable(),
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

    /** table actions */
    public function addComment($job_id, $comment)
    {
        try {
            if (!empty($job_id) && !empty($comment)) {
                $this->addJobHistory([
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => $comment,
                    'job_id' => $job_id
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
}
