<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\JobMatch;
use App\Models\Tutor;
use App\Models\TutorInactiveSchedule;
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


class NewTutorsTable extends PowerGridComponent
{

    use Mailable, Functions;
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

            Detail::make()
                ->view('livewire.admin.components.new-tutor-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Tutor::query()
            ->where('tutor_status', '=', '1')
            ->whereRaw('created_at >= NOW() - INTERVAL 30 DAY');

        return $query->select('tutors.*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('accepted', fn ($tutor) =>  !empty($tutor->price_tutors) ? count($tutor->price_tutors) : 0)
            ->add('jobs_match', function ($tutor) {
                $jobs_match = JobMatch::where('tutor_ids', 'like', $tutor->id)->get();
                return !empty($jobs_match) ? count($jobs_match) : 0;
            })
            ->add('tutor_creat');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Accepted Students')->field('accepted'),
            Column::add()->title('Offers sent')->field('jobs_match'),
            Column::add()->title('Date joined')->field('tutor_creat'),
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

}
