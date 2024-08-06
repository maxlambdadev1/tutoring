<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\Availability;
use App\Models\Tutor;
use App\Models\User;
use App\Models\PostcodeDb;
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


class FindTutorTable extends PowerGridComponent
{
    use WithLeads;
    public $search_input;

    public function setUp(): array
    {
        return [
            Header::make(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.history-detail')
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  $this->findTutorQuery($this->search_input)
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('tutors.id', '=', 'alchemy_sessions.tutor_id')
                    ->on(function ($query) {
                        $query->whereNot('session_status', 6);
                    });
            })
            ->groupBy('tutors.id');

        return $query->select("tutors.*",  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
    }

    public function fields(): PowerGridFields
    {

        return PowerGrid::fields()
            ->add('tutor_name', fn ($tutor) => $tutor->tutor_name)
            ->add('tutor_email', fn ($tutor) => $tutor->tutor_email)
            ->add('tutor_phone', fn ($tutor) => $tutor->tutor_phone)
            ->add('suburb', fn ($tutor) => $tutor->suburb)
            ->add('postcode', fn ($tutor) => $tutor->postcode)
            ->add('distance', function ($tutor) {
                $distance = 0;
                $coords = null;
                if (!empty($this->search_input['suburb'])) {
                    $row = PostcodeDb::where('postcode', $this->search_input['suburb'])->orWhere('suburb', 'like', '%' . $this->search_input['suburb'] . '%')->first();
                    if (!empty($row)) {
                        $coords = [
                            'lat' => $row->lat,
                            'lon' => $row->lon
                        ];
                    }
                }
                if (!empty($coords) && !empty($tutor->lat) && !empty($tutor->lon)) {
                    $distance = $this->calcDistance($coords['lat'], $coords['lon'], $tutor->lat, $tutor->lon);
                }
                if (!empty($distance)) return $distance . 'km';
                else return '-';
            })
            ->add('total_sessions', fn ($tutor) => $tutor->total_sessions)
            ->add('seeking_students', fn ($tutor) => $tutor->seeking_students)
            ->add('tutor_creat', fn ($tutor) => $tutor->tutor_creat)
            ->add('buttons', fn ($tutor) => "<button type='button' class='btn btn-info btn-sm' x-on:click='seeOnMap(" . $tutor->id . ")'>See on map</button>");
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Phone')->field('tutor_phone')->sortable(),
            Column::add()->title('Suburb')->field('suburb'),
            Column::add()->title('Postcode')->field('postcode'),
            Column::add()->title('Distance to tutor')->field('distance'),
            Column::add()->title('Total Sessions')->field('total_sessions'),
            Column::add()->title('Seeking Students')->field('seeking_students')->sortable()->toggleable(false),
            Column::add()->title('Date joined')->field('tutor_creat'),
            Column::add()->title('')->field('buttons'),
        ];
    }
}
