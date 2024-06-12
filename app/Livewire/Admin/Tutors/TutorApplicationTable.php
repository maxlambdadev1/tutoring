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


class TutorApplicationTable extends PowerGridComponent
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
                ->view('livewire.admin.components.tutor-application-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  TutorApplication::query()
            ->leftJoin('alchemy_tutor_application_status', function ($session) {
                $session->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
            });

        if (!empty($this->status)) $query = $query->where('application_status', '=', $this->status);

        return $query->select('alchemy_tutor_application.*', 'alchemy_tutor_application_status.date_last_update', 'alchemy_tutor_application_status.application_status');
    }

    public function fields(): PowerGridFields
    {
        $status = $this::APPLICATION_STATUS;

        return PowerGrid::fields()
            ->add('id')
            ->add('date_submitted', fn ($app) =>  $app->date_submitted ?? '-')
            ->add('date_last_update', fn ($app) =>  $app->date_last_update ?? '-')
            ->add('tutor_first_name', fn ($app) =>  $app->tutor_first_name . ' ' . $app->tutor_last_name ?? '-')
            ->add('application_status', fn ($app) =>  !empty($app->application_status) ? $status[$app->application_status] : '-')
            ->add('tutor_email', fn ($app) =>  $app->tutor_email ?? '-')
            ->add('tutor_phone', fn ($app) =>  $app->tutor_phone ?? '-')
            ->add('tutor_state', fn ($app) =>  $app->tutor_state ?? '-')
            ->add('tutor_suburb',fn ($app) =>  $app->tutor_suburb ?? '-')
            ->add('postcode', function ($app) {
                $temp = true;
                if ($app->postcode != '-' && empty($app->metro_postcode)) $temp = false;
                return $temp;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Date submitted')->field('date_submitted'),
            Column::add()->title('Last status change')->field('date_last_update'),
            Column::add()->title('Tutor name')->field('tutor_first_name')->sortable()->searchable(),
            Column::add()->title('Application status')->field('application_status')->sortable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable(),
            Column::add()->title('Tutor state')->field('tutor_state')->sortable(),
            Column::add()->title('Tutor suburb')->field('tutor_suburb')->sortable(),
            Column::add()->title('Metro')->field('postcode')->toggleable(hasPermission: false, trueLabel: 'Metro', falseLabel: 'Non-metro'),
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

    public function addComment($application_id, $comment)
    {
        if (!empty($application_id) && !empty($comment)) {
            $this->addTutorApplicationHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'application_id' => $application_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    /**
     * @param $app_id, $status : 1 - 9
     */
    public function acceptTutorApplication1($app_id, $app_status)
    { 
        try {
            $this->acceptTutorApplication([
                'application_id' => $app_id,
                'status' => $app_status
            ]);
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'This application was updated!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $app_id
     */
    public function rejectTutorApplication($app_id)
    { 
        try {
            $app = TutorApplication::find($app_id);
            $application_status = $app->application_status;  
            $application_status->update([
                'application_status' => 6,
                'date_follow_up' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);
            
            $params = [
                'tutorfirstname' => $app->tutor_first_name,
                'tutoremail' => $app->tutor_email,
                'appid' => $app->id
            ];
            
            $params['email'] = 'tutor-application-reject';
            $this->tutorApplicationQueue($params);
            
            $this->addTutorApplicationHistory([
                'application_id' => $app->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Changed status to rejected."
            ]);
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'This application was rejected!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }  
    /**
     * @param $app_id
     */
    public function deleteTutorApplication($app_id)
    { 
        try {
            $app = TutorApplication::find($app_id);
            $application_status = $app->application_status; 

            $application_status->delete();
            $app->delete();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'This application was deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
