<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\TutorApplication;
use App\Models\TutorFollowup;
use App\Trait\Functions;
use App\Trait\Sessionable;
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


class TutorFirstSessionTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors, Sessionable;

    // public string $sortField = 'tutor_name';
    // public string $sortDirection = 'asc';

    public function setUp(): array
    {
        $tutor_first_session_status = $this::TUTOR_FIRST_SESSION_STATUS;

        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.tutor-first-session-detail')
                ->params(['tutor_first_session_status' => $tutor_first_session_status])
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Tutor::query()
            ->join('alchemy_tutor_first_session', function ($followup) {
                $followup->on('tutors.id', '=', 'alchemy_tutor_first_session.tutor_id');
            })
            ->where('alchemy_tutor_first_session.status', '!=', 5);

        return $query->select('tutors.*', 'alchemy_tutor_first_session.status', 'alchemy_tutor_first_session.call_date');
    }

    public function fields(): PowerGridFields
    {

        return PowerGrid::fields()
            ->add('id')
            ->add('alchemy_tutor_first_session.status', function ($tutor) {
                $tutor_first_session_status = $this::TUTOR_FIRST_SESSION_STATUS;
                if (!empty($tutor->status)) return $tutor_first_session_status[$tutor->status] ?? '-';
                else return '-';
            })
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('session_date', function ($tutor) {
                $session = Session::where('tutor_id', $tutor->id)->orderBy('id')->first();
                if (!empty($session)) {
                    return $session->session_date . ' ' . $session->session_time;
                } else return '-';
            })
            ->add('call_date', fn ($tutor) =>  $tutor->call_date ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Tutor ID')->field('id')->sortable(),
            Column::add()->title('Status')->field('alchemy_tutor_first_session.status')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Session date')->field('session_date'),
            Column::add()->title('Call date')->field('call_date'),
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
     * @param $tutor_id, $tutor_first_session_status : 1 - 5
     */
    public function updateTutorFirstSessionStatus($tutor_id, $tutor_first_session_status_value)
    { 
        try {
            $tutor_first_session_status = $this::TUTOR_FIRST_SESSION_STATUS;

            $tutor = Tutor::find($tutor_id);
            $tutor_first_session = $tutor->first_session;

            if (!empty($tutor_first_session)) {
                $tutor_first_session->update([
                    'status' => $tutor_first_session_status_value,
                    'date_last_update' => date('d/m/Y H:i')
                ]);

                $this->addTutorHistory([
                    'tutor_id' => $tutor_id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Changed status to ' . $tutor_first_session_status[$tutor_first_session_status_value]
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'This status was updated!'
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
