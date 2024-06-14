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
use Livewire\Attributes\On;


class SetOnlineRoomTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;

    // public string $sortField = 'tutor_name';
    // public string $sortDirection = 'asc';

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
        $query =  Tutor::query()
            ->leftJoin('metro_postcode', function ($followup) {
                $followup->on('tutors.postcode', '=', 'metro_postcode.postcode');
            })
            ->where('tutor_status', '=', 1)
            ->where(function ($query) {
                $query->where('online_url', '=', '')->orWhereNull('online_url');
            });

        return $query->select('tutors.*', 'metro_postcode.postcode as metro_postcode');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('suburb', fn ($tutor) =>  $tutor->suburb ?? '-')
            ->add('online_acceptable_status', fn ($tutor) =>  $tutor->online_acceptable_status ?? '-')
            ->add('metro_postcode', fn ($tutor) =>  !empty($tutor->metro_postcode) ? false : true);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Suburb')->field('suburb'),
            Column::add()->title('Online status')->field('online_acceptable_status')->sortable(),
            Column::add()->title('Non metro')->field('metro_postcode')->sortable()->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
            Column::action('Action'),
        ];
    }

    public function actions($tutor): array
    {
        return [
            Button::add('set-room')
                ->slot('Set room')
                ->class('btn btn-outline-primary waves-effect waves-light btn-sm')
                ->openModal('set-room-modal', ['tutor_id' => $tutor->id]),
        ];
    }
    
    #[On('openModal')]
    public function openModal(string $component, array $arguments)
    {
        $this->dispatch($component, $arguments);
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
