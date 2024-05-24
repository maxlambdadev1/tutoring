<?php

namespace App\Livewire\Admin\Leads;

// use Illuminate\Database\query\Builder;

use App\Models\Job;
use App\Models\Availability;
use App\Models\JobReschedule;
use App\Models\Tutor;
use App\Models\Session;
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
use Carbon\Carbon;
use App\Trait\Functions;
use App\Trait\WithLeads;
use App\Trait\Automationable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;


class AllLeadsTable extends PowerGridComponent
{
    use WithLeads, Automationable, PriceCalculatable, Sessionable, Mailable;

    public string $job_type;

    public function setUp(): array
    {
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

        if ($this->job_type == 'screening') $query = $query->where('hidden', '=', '1')->where('is_from_main', '=', '1');
        else if ($this->job_type == 'new') $query = $query->where('hidden', '=', '1')->where('is_from_main', '=', '0');
        else if ($this->job_type == 'waiting') $query = $query->where('job_status', '=', '3');
        else {
            $query = $query->where('job_status', '=', '0');
            if ($this->job_type == 'active' || $this->job_type == 'focus') $query = $query->where('hidden', '=', '0');
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
            ->add('session_type_id', fn ($job) => $job->session_type->name)
            ->add('parent_name', fn ($job) =>  $job->parent->parent_name)
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
            Column::add()->title('Age')->field('last_updated')->sortable(),
            Column::add()->title('Hidden')->field('hidden')->sortable(),
            Column::add()->title('Date submitted')->field('create_time')->sortable(),
            Column::add()->title('Lead Type')->field('job_type')->sortable(),
            Column::add()->title('Session Type')->field('session_type_id')->sortable(),
            Column::add()->title('Parent')->field('parent_name'),
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
                ->class('btn btn-primary waves-effect waves-light')
                ->toggleDetail(),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->setAttribute('class', 'bg-white'),
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

    /**
     * @param $post = ['assigned_tutor' => 2601, 'availability' => 'mon-7:00 AM', 'custom_date' => 30/05/2024 6:28 AM]
     */
    public function assignLead($job_id, $post)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status == 1) throw new \Exception('This lead was already accepted by someone');
            if (empty($post['assigned_tutor'])) throw new \Exception('Please select a tutor first.');

            $dt_now = new \DateTime('now');
            $start_date = $dt_now->format('d/m/Y');
            $check_start_date = explode('/', $job->start_date);
            if (!empty($check_start_date[1])) $start_date = $job->start_date; //dd/mm/yyyy

            if (!empty($post['availability'])) { //mon-7:00 AM
                $av_arr = explode('-', $post['availability']); //['mon', '7:00 PM']
                $session_date = $this->getNextDateByDay($start_date, $av_arr[0])->format('d/m/Y'); //27/05/2024
                $session_time = Carbon::createFromFormat('g:i A', $av_arr[1])->format('G:i'); //19:00
            } else if (!empty($post['custom_date'])) {
                $custom_date = (new \DateTime('now'))->createFromFormat('d/m/Y h:i A', $post['custom_date']);
                $session_date = $custom_date->format('d/m/Y');
                $session_time = $custom_date->format('H:i');
            } else throw new \Exception('select fields correctly');

            $tutor = Tutor::find($post['assigned_tutor']);
            $job->update([
                'job_status' => 1,
                'accepted_by' => $tutor->id,
                'last_updated' => date('d/m/Y H:i'),
                'accepted_on' => date('d/m/Y H:i'),
                'converted_by' => 'admin'
            ]);

            $tutor->update(['break_count' => 0]);

            $datetime = new \DateTime('Australia/Sydney');
            if (!empty($job->job_offer)) {
                $job_offer = $job->job_offer;
                if ($job_offer->expiry == 'permanent' || $job_offer->expiry >= $datetime->getTimestamp()) {
                    $this->addTutorPriceOffer($tutor->id, $job->parent_id, $job->child_id, $job_offer->offer_amount, $job_offer->offer_type);
                }
            }

            if ($job->job_type == 'creative') {
                $session_price = 100;
                if ($job->session_type_id == 1) $tutor_price = 65;
                else $tutor_price = 50;
            } else {
                $session_price = $this->calcSessionPrice($job->parent_id, $job->session_type_id);
                $tutor_price = $this->calcTutorPrice($tutor->id, $job->parent_id, $job->child_id, $job->session_type_id);
            }

            $session_status = 3;
            $today = new \DateTime('now');
            $today->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            $ses_date = \DateTime::createFromFormat('d/m/Y H:i', $session_date . ' ' . $session_time);
            $ses_date->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            if ($today->getTimestamp() >= $ses_date->getTimestamp()) $session_status = 1;

            $this->checkTutorFirstSession($tutor->id);
            $session = Session::create([
                'session_status' => $session_status,
                'tutor_id' => $tutor->id,
                'parent_id' => $job->parent_id,
                'child_id' => $job->child_id,
                'session_date' => $session_date,
                'session_time' => $session_time,
                'session_subject' => $job->subject,
                'session_is_first' => 1,
                'session_price' => $session_price,
                'session_tutor_price' => $tutor_price,
                'session_last_changed' => date('d/m/Y H:i'),
                'type_id' => $job->session_type_id
            ]);

            $parent = $job->parent;
            $child = $job->child;
            $params = [
                'firstname' => $tutor->first_name,
                'studentname' => $child->child_name,
                'studentfirstname' => $child->child_first_name,
                'grade' => $child->child_year,
                'date' => $session_date,
                'time' => $session_time,
                'subject' => $job->subject,
                'notes' => $job->job_notes,
                'address' => $parent->parent_address . ', ' . $parent->parent_suburb . ', ' . $parent->parent_postcode,
                'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                'parentphone' => $parent->parent_phone,
                'email' => $tutor->user->email,
                'tutor_email' => $tutor->user->email
            ];

            if ($job->job_type == 'creative') {
                // if ($job->session_type_id == 1) $this->sendEmail($params, 'tutor-creative-session-details-email');
                // else $this->sendEmail($params, 'tutor-creative-online-session-details-email');
            } else {
                // if ($job->session_type_id == 1) $this->sendEmail($params, 'tutor-first-session-details-email');
                // else $this->sendEmail($params, 'tutor-first-online-session-details-email');
            }

            $p = array(
                'phone' => $params['parentphone'],
                'name' => $params['parentname']
            );
            if ($job->job_type == 'creative') {
                $sms_body = "Hi " . $params['tutorfirstname'] . "! Your creative writing workshop with " . $params['studentname'] . " has been confirmed for " . $params['time'] . " on " . $params['date'] . ". You will receive an email with details shortly! Team Alchemy";
            } else {
                $sms_body = "Huzzah! Your first session with " . $params['studentname'] . " is confirmed for " . $params['date'] . " at " . $params['time'] . ". Please check your email for details and don’t hesitate to get in touch with any questions!";
            }
            // $this->sendSms($p, $sms_body);

            $params['tutorname'] = $tutor->tutor_name;
            $params['tutorfirstname'] = $tutor->first_name;
            $params['parentfirstname'] = $parent->parent_first_name;
            $params['tutorphone'] = $tutor->tutor_phone;
            $params['price'] = $session_price;
            $params['email'] = $parent->user->email;
            $params['onlineurl'] = $tutor->online_url;
            $params['tutorlink'] = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/tutor/' . $tutor->id;

            if ($job->job_type == 'creative') {
                // if ($job->session_type_id == 1) $this->sendEmail($params, 'parent-creative-session-details-email');
                // else $this->sendEmail($params, 'parent-creative-online-session-details-email');
            } else {
                // if ($job->session_type_id == 1) $this->sendEmail($params, 'parent-first-session-detail-email');
                // else $this->sendEmail($params, 'parent-first-online-session-detail-email');
            }

			$notified_tutors = array();
            $reschedules = JobReschedule::where('job_id', $job->id)->get();
            if (!empty($reschedules)) {
                foreach ($reschedules as $reschedule) {
                    $reschedule_tutor = Tutor::find($reschedule->tutor_id);
                    if ($tutor->id !== $reschedule_tutor->id) {
                        $params = [
                            'tutorfirstname' => $reschedule_tutor->tutor_name,
                            'grade' => $child->child_year,
                            'address' => $job->location,
                            'email' => $reschedule_tutor->user->email
                        ];
                        if (!in_array($reschedule_tutor->id, $notified_tutors)) {
                            // $this->sendEmail($params, 'tutor-accept-lead-alternate-date');
                            array_push($notified_tutors, $reschedule_tutor->id);
                        }
                    }
                    $reschedule->delete();
                }
            }

            $job->update([
                'session_id' => $session->id,
                'last_updated' => date('d/m/Y H:i'),                
            ]);

            $this->addConversionTarget($job->id, $session->id);

            if ($job->job_type !== 'creative') {
                $this->addFirstSessionTarget($session->id);
            } else {
                $send_to = 'alecks.annear@alchemytuition.com.au';
                $params = [
                    'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                    'studentname' => $child->child_name,
                    'studentbirthday' => $child->child_birthday,
                    'vouchernumber' => $job->voucher_number
                ];
                $this->sendEmail($send_to, 'new-creative-kids-creation', $params);
            }

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
}
