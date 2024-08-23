<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use Illuminate\Support\Facades\DB;
use App\Models\Job;
use App\Models\Availability;
use App\Models\Tutor;
use App\Models\User;
use App\Models\WaitingLeadOffer;
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
use App\Trait\Functions;
use App\Trait\WithLeads;
use App\Trait\Automationable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;


class AllLeadsTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public string $job_type;

    public function setUp(): array
    {
        $this; 

        $total_availabilities = Availability::get();
        $progress_list = $this::PROGRESS_STATUS;
        $week_days = $this::WEEK_DAYS;
        $all_tutors = Tutor::get();
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.lead-detail')
                ->params(['total_availabilities' => $total_availabilities, 'progress_list' => $progress_list, 'all_tutors' => $all_tutors, 'week_days' => $week_days])
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Job::query()
            ->where('job_type', '!=', 'creative')
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_jobs.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_jobs.child_id', '=', 'alchemy_children.id');
            });

        if ($this->job_type == 'waiting') $query = $query->where('job_status', '=', '3');
        else if ($this->job_type == 'special-requirement') $query = $query->where('job_status', '=', '4');
        else {
            $datetime = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
            $formattedDate = $datetime->format('d/m/Y H:i');

            $query = $query->where('job_status', '=', '0');
            if ($this->job_type == 'screening') $query = $query->where('hidden', '=', '1')->where('is_from_main', '=', '1');
            else if ($this->job_type == 'new') $query = $query->where('hidden', '=', '1')->where('is_from_main', '=', '0');
            else if ($this->job_type == 'active') $query = $query->where('hidden', '=', '0')->whereRaw("TIMESTAMPDIFF(HOUR, STR_TO_DATE(last_updated, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $formattedDate . "', '%d/%m/%Y %H:%i')) <= 48");
            else if ($this->job_type == 'focus') $query = $query->where('hidden', '=', '0')->whereRaw("TIMESTAMPDIFF(HOUR, STR_TO_DATE(last_updated, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $formattedDate . "', '%d/%m/%Y %H:%i')) > 48");
        }

        return $query->select('alchemy_jobs.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_email',
                'parent_phone',
                'parent_first_name',
                'parent_last_name',
                'child_name',
                'parent_state',
                'parent_address',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('last_updated', function ($job) {
                $dtime = \DateTime::createFromFormat("d/m/Y H:i", $job->last_updated);
                $dtime->setTimeZone(new \DateTimeZone('Australia/Sydney'));
                $timestamp = $dtime->getTimestamp();
                $age = round((time() - $timestamp) / 3600, 0);
                return $age;
            })
            ->add('hidden', fn ($job) => $job->hidden ? 'Yes' : 'No')
            ->add('create_time')
            ->add('job_type', fn ($job) => $job->job_type == 'regular' ? '' : $job->job_type)
            ->add('session_type_id', fn ($job) => $job->session_type->name ?? 'Online')
            ->add('parent_first_name', fn ($job) =>  $job->parent->parent_first_name . ' '.$job->parent->parent_last_name )
            ->add('parent_phone', fn ($job) =>  $job->parent->parent_phone)
            ->add('parent_email', fn ($job) =>  $job->parent->parent_email)
            ->add('student_name', fn ($job) =>  $job->child->child_name)
            ->add('student_grade', fn ($job) =>  $job->child->child_year)
            ->add('subject', fn ($job) =>  $job->subject)
            ->add('state', fn ($job) =>  $job->parent->parent_state)
            ->add('address', fn ($job) =>  $job->parent->parent_address . " " . $job->location)
            ->add('progress_status', fn ($job) =>  $job->progress_status);
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Age')->field('last_updated'),
            Column::add()->title('Hidden')->field('hidden')->sortable(),
            Column::add()->title('Date submitted')->field('create_time')->sortable(),
            Column::add()->title('Lead Type')->field('job_type')->sortable(),
            Column::add()->title('Session Type')->field('session_type_id')->sortable(),
            Column::add()->title('Parent')->field('parent_first_name')->sortable(),
            Column::add()->title('Parent Phone')->field('parent_phone')->searchable()->sortable(),
            Column::add()->title('Parent Email')->field('parent_email')->searchable()->sortable(),
            Column::add()->title('Student')->field('student_name', 'child_name')->sortable()->searchable(),
            Column::add()->title('Grade')->field('student_grade', 'child_year')->sortable(),
            Column::add()->title('Subject')->field('subject', 'subject')->sortable(),
            Column::add()->title('State')->field('state', 'parent_state')->sortable()->searchable(),
            Column::add()->title('Progress')->field('progress_status', 'progress_status')->sortable(),
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

    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->when(fn ($job) => $job->job_status == 4) //for special-requirement lead
                ->detailView('livewire.admin.components.special-requirement-lead-detail'),
        ];
    }

    /** table actions */
    public function addComment($job_id, $comment)
    {
        if (!empty($job_id) && !empty($comment)) {
            $this->addJobHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment,
                'job_id' => $job_id
            ]);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The comment was saved successfully.'
            ]);
        }
    }

    public function updateProgressStatus($job_id, $status)
    {
        Job::find($job_id)->update(['progress_status' => $status]);
        $this->dispatch('showToastrMessage', [
            'status' => 'success',
            'message' => 'The progress status was updated successfully.'
        ]);
    }

    public function matchLead($job_id)
    {
        $this->findTutorForJob($job_id);
        $this->dispatch('showToastrMessage', [
            'status' => 'success',
            'message' => 'Automation for this job was run successfully.'
        ]);
    }

    public function sendOnlineEmail($job_id)
    {
        try {
            $this->sendOnlineTutoringEmail($job_id);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The online tutoring email just was sent!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendWelcomeEmail($job_id)
    {
        try {
            $this->sendWelcomeEmailAndSms($job_id);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Welcome email and sms sent!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendToWaitingList1($job_id)
    {
        try {
            $this->sendToWaitingList($job_id);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Welcome email and sms sent!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function assignLead1($job_id, $post)
    {
        try {
            $this->assignLead($job_id, $post);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The lead was assigned successfully'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function toggleShowHideLead1($job_id)
    {
        try {
            $this->toggleShowHideLead($job_id);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly edited the lead'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteLead1($job_id, $reason)
    {
        try {
            $this->deleteLead($job_id, $reason);
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly edited the lead'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function approveAndRelease($job_id)
    {
        try {
            $job = Job::find($job_id);
            $job->update([
                'hidden' => 0,
                'automation' => 1,
                'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $admin = auth()->user()->admin;
            $this->addJobHistory([
                'job_id' => $job_id,
                'author' => $admin->admin_name,
                'comment' => 'Approved and released.'
            ]);

            if ($job->job_status == 0 && $job->welcome_call == 0) {
                $parent = $job->parent;
                $child = $job->child;
                $params = [
                    'userfirstname' => $admin->first_name,
                    'username' => $admin->admin_name,
                    'useremail' => $admin->user->email,
                    'parentfirstname' => $parent->parent_first_name,
                    'studentname' => $child->child_first_name,
                    'email' => $parent->parent_email
                ];
                $this->sendEmail($parent->parent_email, 'parent-welcome-call-email', $params);
                $sms_params = [
                    'phone' => $parent->parent_phone,
                    'name' => $parent->parent_name,
                ];
                $this->sendSms($sms_params, 'parent-welcome-call-sms', $params);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Approved and released successfully.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function furtherContactRequiredLead($job_id, $reason)
    {
        try {
            $job = Job::find($job_id);
            $job->update([
                'is_from_main' => 0,
                'last_updated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $this->addJobHistory([
                'job_id' => $job_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => $reason
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'You successfuly submitted the reason'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendToCurrentLeads($job_id)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status !== 3) throw new \Exception('This lead is not in waiting list');

            $job->update([
                'job_status' => 0,
                'last_updated_for_waiting_list' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $this->addJobHistory([
                'job_id' => $job_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'The lead was sent to the waiting list.'
            ]);

            if (!empty($job->waiting_lead_offer)) {
                foreach ($job->waiting_lead_offer as $waiting_offer) {
                    if ($waiting_offer->status == 0) $waiting_offer->update(['status' => 1]);
                }
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The lead was sent to the current list successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function rejectFromWaitingList($job_id)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status !== 3) throw new \Exception('This lead is not in waiting list');
            
            $waitings = WaitingLeadOffer::where('job_id', $job_id)->where('status', 0)->get();
            if (!empty($waitings)) {
                foreach ($waitings as $waiting) {
                    $waiting->update(['status' => 1]);

                    $admin = auth()->user()->admin;
                    $tutor = Tutor::find($waiting->tutor_id);
                    $child = $job->child;

                    $this->addJobHistory([
                        'job_id' => $job_id,
                        'author' => $admin->admin_name,
                        'comment' => "The tutor " . $tutor->tutor_name . "(" . $tutor->user->email . ") was rejected in this job"
                    ]);

                    $params = [
                        'email' => $tutor->user->email,
                        'tutorfirstname' => $tutor->first_name,
                        'childname' => $child->child_name,
                        'childyear' => $child->child_year,
                        'subject' => $job->subject,
                        'location' => $job->session_type_id == 1 ? $job->location : 'Online'
                    ];
                    $this->sendEmail($tutor->user->email, 'reject-application-waiting-list-from-admin-email', $params);

                }
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The application was rejected successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function approveSpecialRequirementResponse($job_id)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status !== 4) throw new \Exception('Invalid job for special requirement');

            $this->addTutorHistory([
                'tutor_id' => $job->tutor->id,
                'author' => auth()->user()->admin->admin_name ?? '',
                'comment' => 'Admin allowed your request for sepcial-requirement job(job-id=' . $job->id .')'
            ]);

            $ses_date = $this->generateSessionDate($job->tutor_suggested_session_date, $job->start_date, false);

            $this->createSessionFromJob($job_id, $job->tutor->id, $ses_date['date'], $ses_date['time']);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Approved successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function rejectSpecialRequirementResponse($job_id)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status !== 4) throw new \Exception('Invalid job for special requirement');
            
            $tutor = $job->tutor;

            $job->update([
                'job_status' => 0,
                'accepted_by' => null,
                'last_updated' => date('d/m/Y H:i'),
                'converted_by' => null,
                'special_request_response' => ''
            ]);

            $this->addTutorHistory([
                'tutor_id' => $job->tutor->id,
                'author' => auth()->user()->admin->admin_name ?? '',
                'comment' => 'Tutor rejected from special-requirement job - job-id=' . $job->id
            ]);

            $params = [
                'tutorfirstname' => $tutor->first_name,
                'subject' => $job->subject,
                'grade' => $job->child->child_year,
                'sessiontype' => $job->session_type_id == 1 ? 'FACE TO FACE' : 'Online',
                'specialrequirement' => $job->special_request_content
            ];

            $this->sendEmail($tutor->user->email, 'special-requirement-tutor-reject-email', $params);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Rejected successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
