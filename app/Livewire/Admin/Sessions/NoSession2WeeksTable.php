<?php

namespace App\Livewire\Admin\Sessions;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
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


class NoSession2WeeksTable extends PowerGridComponent
{

    use Functions;
    
    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.no-session-2-weeks-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Session::query()
            ->join('alchemy_children as child', 'alchemy_sessions.child_id', '=', 'child.id')
            ->where('alchemy_sessions.session_status', 3)
            ->where('child.child_status', 1)
            ->whereExists(function ($query) {
                $query->select(DB::raw("COUNT(id) as cnt"))
                    ->from('alchemy_sessions as ses2')
                    ->whereRaw('ses2.id = alchemy_sessions.session_previous_session_id')
                    ->whereIn('ses2.session_status', [2, 4])
                    ->whereRaw('STR_TO_DATE(ses2.session_last_changed, "%d/%m/%Y %H:%i") < (NOW() - INTERVAL 14 DAY)')
                    ->havingRaw('cnt > 0');
            })
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_sessions.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            });

        return $query->select('alchemy_sessions.*');
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
            ->add('id')
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('tutor_name', fn ($ses) =>  $ses->tutor->tutor_name ?? '-')
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('prev_session', fn ($ses) =>  !empty($ses->prev_session) ? $ses->prev_session->session_date : ($ses->session_is_first == 1 ? 'First session' : '-'))
            ->add('session_reason', fn ($ses) => $ses->session_date ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Previous session')->field('prev_session'),
            Column::add()->title('Session reason')->field('session_reason'),
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
    
    public function addComment($ses_id, $comment)
    {
        if (!empty($ses_id) && !empty($comment)) {
            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'session_id' => $ses_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

}
