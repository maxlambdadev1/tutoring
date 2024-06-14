<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\TutorApplication;
use App\Models\TutorApplicationReference;
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


class HaveReferencesTutorTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;

    public string $sortField = 'tutor_first_name';
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
                ->view('livewire.admin.components.tutor-have-references-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $query =  TutorApplication::query()
            ->join('alchemy_tutor_application_status', function ($followup) {
                $followup->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
            })
            ->where('application_status', '=', 5)
            ->where('reference_respond_status', '=', 0)
            ->whereNotNull('reference_update')
            ->whereNotNull('tutor_email1')
            ->whereNotNull('tutor_email2');

        return $query->select('alchemy_tutor_application.*');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_first_name', fn ($app) =>  $app->tutor_first_name . ' ' .  $app->tutor_last_name?? '-')
            ->add('tutor_email', fn ($app) =>  $app->tutor_email?? '-')
            ->add('tutor_phone', fn ($app) =>  $app->tutor_phone?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('APP ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_first_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::action('Action'),
        ];
    }

    public function actions($tutor): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-primary waves-effect waves-light btn-sm')
                ->toggleDetail()
        ];
    }

    public function addComment($app_id, $comment)
    {
        if (!empty($app_id) && !empty($comment)) {
            $this->addTutorApplicationReferenceHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'application_id' => $app_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }
    
    public function updateReference1($app_id, $first_name, $last_name, $email, $relation)
    {
        try {
            $app = TutorApplication::find($app_id);
            $app_reference = TutorApplicationReference::where('application_id')->where('reference_email', $email)->first();

            if (!empty($app_reference) && $app->tutor_email1 == $email) {
                throw new \Exception("The old reference already responded, so you can't update the email of this reference.");
            } else {
                $app->update([
                    'tutor_fname1' => $first_name,
                    'tutor_lname1' => $last_name,
                    'tutor_email1' => $email,
                    'tutor_relation1' => $relation,
                    'reference_update' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The reference was successfuly updated"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function emailToReference1($app_id)
    {
        try {
            $app = TutorApplication::find($app_id);
            $app_reference = TutorApplicationReference::where('application_id')->where('reference_email', $app->tutor_email1)->first();

            if (!empty($app_reference)) {
                throw new \Exception("This reference already responded, so you can't send the email for this reference.");
            } else {
                $shared_secret = 'happykahala!0987654321@1234567890#';
                $secret = sha1($app->tutor_email1 . $shared_secret);
                $params = [
                    'email' => $app->tutor_email1,
                    'tutorname' => $app->tutor_first_name . ' ' . $app->tutor_last_name,
                    'tutorfirstname' => $app->tutor_first_name,
                    'referencefirstname' => $app->tutor_fname1,
                    'link' => 'https://alchemy.team/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $app->tutor_email1 . '&application_id=' . $app->id . '&reason=no'),
                    'reasonlink' => 'https://alchemy.team/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $app->tutor_email1 . '&application_id=' . $app->id . '&reason=yes'),
                ];
                $this->sendEmail($app->tutor_email1, 'tutor-application-reference-email', $params);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The email was successfuly sent"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateReference2($app_id, $first_name, $last_name, $email, $relation)
    {
        try {
            $app = TutorApplication::find($app_id);
            $app_reference = TutorApplicationReference::where('application_id')->where('reference_email', $email)->first();

            if (!empty($app_reference) && $app->tutor_email1 == $email) {
                throw new \Exception("The old reference already responded, so you can't update the email of this reference.");
            } else {
                $app->update([
                    'tutor_fname2' => $first_name,
                    'tutor_lname2' => $last_name,
                    'tutor_email2' => $email,
                    'tutor_relation2' => $relation,
                    'reference_update' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The reference was successfuly updated"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function emailToReference2($app_id)
    {
        try {
            $app = TutorApplication::find($app_id);
            $app_reference = TutorApplicationReference::where('application_id')->where('reference_email', $app->tutor_email2)->first();

            if (!empty($app_reference)) {
                throw new \Exception("This reference already responded, so you can't send the email for this reference.");
            } else {
                $shared_secret = 'happykahala!0987654321@1234567890#';
                $secret = sha1($app->tutor_email2 . $shared_secret);
                $params = [
                    'email' => $app->tutor_email2,
                    'tutorname' => $app->tutor_first_name . ' ' . $app->tutor_last_name,
                    'tutorfirstname' => $app->tutor_first_name,
                    'referencefirstname' => $app->tutor_fname2,
                    'link' => 'https://alchemy.team/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $app->tutor_email2 . '&application_id=' . $app->id . '&reason=no'),
                    'reasonlink' => 'https://alchemy.team/reference?url=' . base64_encode('secret=' . $secret . '&email=' . $app->tutor_email2 . '&application_id=' . $app->id . '&reason=yes'),
                ];
                $this->sendEmail($app->tutor_email2, 'tutor-application-reference-email', $params);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "The email was successfuly sent"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
