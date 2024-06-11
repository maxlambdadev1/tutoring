<?php

namespace App\Livewire\Admin\Sessions;

use Illuminate\Support\Facades\DB;
use App\Models\SessionReschedule;
use App\Models\Session;
use App\Models\User;
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


class RescheduleSessionTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public string $sortField = 'session_id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.rescheduled-session-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  SessionReschedule::query()
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('alchemy_sessions_reschedule.session_id', '=', 'alchemy_sessions.id');
            })
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_sessions.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->where('alchemy_sessions_reschedule.hidden', 0)
            ->where(function ($query) {
                $query->where('session_status', 1)->orWhere('session_status', 3);
            })
            ->groupBy('alchemy_sessions_reschedule.session_id')
            ->having('cnt', '>', 2);

        return $query->select('alchemy_sessions_reschedule.session_id', 'alchemy_sessions.id', DB::raw('COUNT(alchemy_sessions_reschedule.session_id) as cnt'));
    }

    public function relationSearch(): array
    {
        return [
            'session' => [
                'alchemy_parent' => [
                    'parent_first_name'
                ],
                'tutors' => [
                    'tutor_name'
                ],
                'alchemy_children' => [
                    'child_name'
                ]
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('tutor_name', fn ($item) => $item->session->tutor->tutor_name ?? '-')
            ->add('parent_first_name', fn ($item) => $item->session->parent->parent_first_name . ' ' . $item->session->parent->parent_last_name ?? '-')
            ->add('child_name', fn ($item) => $item->session->child->child_name)
            ->add('session_date', fn ($item) => $item->session->session_date)
            ->add('cnt', fn ($item) => $item->cnt);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Session date')->field('session_date'),
            Column::add()->title('Reschedule count')->field('cnt'),
            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-info waves-effect waves-light btn-sm')
                ->toggleDetail(),
        ];
    }

    /** table actions */
    public function addComment($ses_id, $comment)
    {
        try {
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
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /** send to session to reschedule unhidden */
    public function editRescheduleSession($ses_id)
    {
        try {
            SessionReschedule::where('session_id', $ses_id)->update([
                'hidden' => 1
            ]);

            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Marked this session as hidden in reshcheduled sessions.',
                'session_id' => $ses_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Session updated!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
