<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\TutorApplication;
use App\Models\TutorFollowup;
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


class TutorCheckInTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;

    public string $sortField = 'tutor_name';
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
                ->view('livewire.admin.components.tutor-check-in-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Tutor::query()
            ->leftJoin('alchemy_tutor_followup', function ($followup) {
                $followup->on('tutors.id', '=', 'alchemy_tutor_followup.tutor_id');
            })
            ->where('tutor_status', '=', 1);

        if ($this->filter == 'active') {
            $query = $query->where(function ($query1) {
                $query1->whereNull('alchemy_tutor_followup.timestamp')->orWhere('alchemy_tutor_followup.timestamp', '<', time());
            });
        }

        return $query->select('tutors.*', 'alchemy_tutor_followup.timestamp');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('tutor_creat', fn ($tutor) =>  $tutor->tutor_creat ?? '-')
            ->add('alchemy_tutor_followup.timestamp', function ($tutor) {
                if (!empty($tutor->timestamp)) { 
                    $time = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
                    $time->setTimestamp($tutor->timestamp);
                    return $time->format('d/m/Y');
                } else return '-';
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Join date')->field('tutor_creat'),
            Column::add()->title('Followup date')->field('alchemy_tutor_followup.timestamp'),
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

    public function addComment($tutor_id, $comment)
    {
        if (!empty($tutor_id) && !empty($comment)) {
            $this->addTutorHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'tutor_id' => $tutor_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    /**
     * @param $tutor_id, $in_six_weeks : true or false, $schedule_date : 'mm/dd/YYYY'
     */
    public function tutorCheckInAddFollowup($tutor_id, $in_six_weeks, $schedule_date)
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
                TutorFollowup::updateOrCreate([
                    'tutor_id' => $tutor_id,
                ], [
                    'timestamp' => $timestamp
                ]);

                $this->addTutorHistory([
                    'tutor_id' => $tutor_id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => $comment
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'This application was updated!'
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
