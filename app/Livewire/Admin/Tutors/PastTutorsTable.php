<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
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


class PastTutorsTable extends PowerGridComponent
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
                ->view('livewire.admin.components.current-tutor-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Tutor::query()
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('tutors.id', '=', 'alchemy_sessions.tutor_id')
                    ->on(function ($query) {
                        $query->where('session_status', 2)->orWhere('session_status', 4);
                    });
            })
            ->where('tutor_status', '=', '0')
            ->groupBy('tutors.id');

        return $query->select('tutors.*', DB::raw('COUNT(alchemy_sessions.id) as total_sessions'));
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($tutor) =>  $tutor->tutor_name ?? '-')
            ->add('tutor_phone', fn ($tutor) =>  $tutor->tutor_phone ?? '-')
            ->add('tutor_email', fn ($tutor) =>  $tutor->tutor_email ?? '-')
            ->add('total_sessions', fn ($tutor) =>  $tutor->total_sessions ?? '-')
            ->add('suburb', fn ($tutor) =>  $tutor->suburb ?? '-')
            ->add('state', fn ($tutor) =>  $tutor->state ?? '-')
            ->add('have_wwcc')
            ->add('ABN', fn ($tutor) =>  !empty($tutor->ABN) ? true : false)
            ->add('accept_job_status')
            ->add('non_metro')
            ->add('seeking_students')
            ->add('experienced')
            ->add('mature')
            ->add('tutor_creat');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Total sessions')->field('total_sessions')->sortable()->searchable(),
            Column::add()->title('Suburb')->field('suburb')->sortable(),
            Column::add()->title('State')->field('state')->sortable(),
            Column::add()->title('WWCC held')->field('have_wwcc')->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
            Column::add()->title('ABN held')->field('ABN')->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
            Column::add()->title('Can accept jobs')->field('accept_job_status')->sortable()->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
            Column::add()->title('Metro status')->field('non_metro')->sortable()->toggleable(hasPermission: false, trueLabel: 'Non-Metro', falseLabel: 'Metro'),
            Column::add()->title('Seeking students')->field('seeking_students')->sortable()->toggleable(hasPermission: false, trueLabel: 'On', falseLabel: 'Off'),
            Column::add()->title('Experienced')->field('experienced')->sortable()->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
            Column::add()->title('Mature')->field('mature')->toggleable(hasPermission: false, trueLabel: 'yes', falseLabel: 'no'),
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

    /**
     * @param $tutor_id, $is_now: true or false, $schedule_date : '25/05/2024'
     */
    public function makeTutorActive($tutor_id)
    {
        try {
            $tutor = Tutor::find($tutor_id);
            $tutor->update(['tutor_status' => 1]);

            // register current tutor to Tutorhub.
            $user_name = 'nicroth';
            $token = 'YS5C MwDY BnYR Xo7z iwt4 kj7T';
            $endpoint = 'https://tutorhub.alchemytuition.com.au/wp-json/wp/v2/users';
            $unique_password = 'Moschino121!';
            $name = explode(' ',$tutor->tutor_name);
            $user_info = array(
                'username' => $tutor->tutor_email,
                'name' => $tutor->tutor_name,
                'email' => $tutor->tutor_email,
                'password' => $unique_password,
                'first_name' => $name[0],
                'last_name' => $name[1],
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $user_name . ":" . $token);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($user_info));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);

            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => "This tutor changed to active."
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The tutor is now active!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function blockTutorFromJobs($tutor_id)
    {
        try {
            $tutor = Tutor::find($tutor_id);

            $tutor->update([
                'accept_job_status' => 0
            ]);

            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Blocked tutor from accepting jobs.'
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The tutor can't accept jobs from now on!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeOnlineStatus($tutor_id)
    {
        try {
            $tutor = Tutor::find($tutor_id);
            $status = $tutor->online_acceptable_status == 1 ? 0 : 1;

            $tutor->update([
                'online_acceptable_status' => $status
            ]);

            $this->addTutorHistory([
                'tutor_id' => $tutor->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'The online status was changed to ' . $status
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The online status was changed successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
