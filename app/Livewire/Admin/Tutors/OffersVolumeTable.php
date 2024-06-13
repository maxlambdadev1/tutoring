<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\TutorApplication;
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


class OffersVolumeTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;

    // public string $sortField = 'tutor_name';
    // public string $sortDirection = 'asc';
    public $status;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.dormant-tutor-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Tutor::query()
            ->leftJoin('alchemy_tutor_offers_volume', function ($volume) {
                $volume->on('tutors.id', '=', 'alchemy_tutor_offers_volume.tutor_id');
            })
            ->where('tutor_status', '=', 1)
            ->where('alchemy_tutor_offers_volume.hidden', '=', 0)
            ->where('alchemy_tutor_offers_volume.offers', '>=', 6);

        return $query->select('tutors.*', 'alchemy_tutor_offers_volume.offers');
    }

    public function fields(): PowerGridFields
    {
        $status = $this::APPLICATION_STATUS;

        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('offers', fn ($tutor) =>  $tutor->offers ?? '-')
            ->add('tutor_creat', fn ($tutor) =>  $tutor->tutor_creat ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Offers volume')->field('offers'),
            Column::add()->title('Join date')->field('tutor_creat'),
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
