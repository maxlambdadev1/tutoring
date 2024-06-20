<?php

namespace App\Livewire\Admin\Parents;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\AlchemyParent;
use App\Models\ParentFollowup;
use App\Trait\Functions;
use App\Trait\WithTutors;
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


class ParentCheckInTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;

    public string $sortField = 'parent_first_name';
    public string $sortDirection = 'asc';
    public $filter;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.parent-check-in-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  AlchemyParent::query()
            ->leftJoin('alchemy_parent_followup', function ($followup) {
                $followup->on('alchemy_parent.id', '=', 'alchemy_parent_followup.parent_id');
            })
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('alchemy_parent.id', '=', 'alchemy_sessions.parent_id')
                    ->on(function ($query) {
                        $query->where('session_status', 2);
                    });
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('alchemy_children as c')
                      ->whereRaw('c.parent_id = alchemy_parent.id')
                      ->where('c.child_status', 1);
            })
            ->having('total_sessions', '>=', 3)
            ->groupBy('alchemy_parent.id');

        if ($this->filter == 'active') {
            $query = $query->where(function ($query1) {
                $query1->whereNull('alchemy_parent_followup.timestamp')->orWhere('alchemy_parent_followup.timestamp', '<', time());
            });
        }

        return $query->select('alchemy_parent.*', 'alchemy_parent_followup.timestamp',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('parent_first_name', fn ($item) =>  $item->parent_first_name . ' ' . $item->parent_last_name ?? '-')
            ->add('parent_email', fn ($item) =>  $item->parent_email ?? '-')
            ->add('parent_phone', fn ($item) =>  $item->parent_phone ?? '-')
            ->add('parent_create', function($item) {
                $job = Job::where('parent_id', $item->id)->orderBy('id', 'asc')->first();
                if (!empty($job)) return $job->create_time;
                else return '-';
            })
            ->add('alchemy_parent_followup.timestamp', function ($item) {
                if (!empty($item->timestamp)) { 
                    $time = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
                    $time->setTimestamp($item->timestamp);
                    return $time->format('d/m/Y');
                } else return '-';
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Parent phone')->field('parent_phone')->sortable()->searchable(),
            Column::add()->title('Join date')->field('parent_create'),
            Column::add()->title('Followup date')->field('alchemy_parent_followup.timestamp')->sortable(),
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

    public function addComment($parent_id, $comment)
    {
        if (!empty($parent_id) && !empty($comment)) {
            $this->addParentHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'parent_id' => $parent_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    /**
     * @param $parent_id, $in_six_weeks : true or false, $schedule_date : 'mm/dd/YYYY'
     */
    public function parentCheckInAddFollowup($parent_id, $in_six_weeks, $schedule_date)
    { 
        try {
            $date = new \DateTime('now');
            if ($in_six_weeks)  {
                $timestamp = $date->modify('+6 weeks')->getTimestamp();
                $comment = 'Added follow up date for check in on '  .$date->modify('+6 weeks')->format('d/m/Y');
            } else {
                if (!empty($schedule_date)) {
                    $timestamp = \DateTime::createFromFormat('d/m/Y', $schedule_date)->getTimestamp();
                    $comment = 'Added follow up date for check in ' . $schedule_date;
                }
            } 
            
            if (!empty($timestamp)) {
                ParentFollowup::updateOrCreate([
                    'parent_id' => $parent_id,
                ], [
                    'timestamp' => $timestamp
                ]);

                $this->addParentHistory([
                    'parent_id' => $parent_id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => $comment
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'This parent was updated!'
                ]);
            } else {
                $this->dispatch('showToastrMessage', [
                    'status' => 'error',
                    'message' => 'Invalid date'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
