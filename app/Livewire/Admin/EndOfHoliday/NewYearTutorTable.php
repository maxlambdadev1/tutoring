<?php

namespace App\Livewire\Admin\EndOfHoliday;

use App\Trait\Mailable;
use App\Models\HolidayStudent;
use Illuminate\Support\Facades\DB;
use App\Models\HolidayTutor;
use App\Models\PriceTutor;
use App\Models\Tutor;
use App\Models\HolidayTemp;
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


class NewYearTutorTable extends PowerGridComponent
{

    use Mailable, Functions;
    public string $sortField = 'id';
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
                ->view('livewire.admin.components.new-year-tutor-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  HolidayTutor::query()
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('tutors.id', '=', 'alchemy_holiday_tutor.tutor_id');
            });

        return $query->select('alchemy_holiday_tutor.*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('status', function ($item){
                if ($item->status == 1) return 'Awaiting';
                else if ($item->status == 2) return 'Continuing';
                else if ($item->status == 3) return 'Not continuing';

                return '-';
            })
            ->add('tutor_name', fn ($item) =>  $item->tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($item) =>  $item->tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($item) =>  $item->tutor->tutor_phone ?? '-')
            ->add('date_created')
            ->add('date_last_modified');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Status')->field('status')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Date created')->field('date_created'),
            Column::add()->title('Last modified')->field('date_last_modified'),
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

    public function addComment($holiday_id, $comment)
    {
        if (!empty($holiday_id) && !empty($comment)) {
            $this->addHolidayTutorHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'holiday_id' => $holiday_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }
    
}
