<?php

namespace App\Livewire\Admin\Sessions;

use App\Trait\Sessionable;
use App\Trait\WithParents;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Child;
use App\Models\AlchemyParent;
use App\Models\Job;
use App\Models\SessionFilter;
use App\Models\TutorConnected;
use Illuminate\Support\Collection;
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


class NoScheduledSessionsTable extends PowerGridComponent
{
    use Sessionable, WithParents;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public string $filter = '';


    public function setUp(): array
    {
        $filter_array = $this::NO_SESSION_FILTER_ARRAY;

        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.no-scheduled-session-detail')
                ->showCollapseIcon()
                ->params(['filter_array' => $filter_array])
        ];
    }

    public function datasource(): ?Collection
    {
        $filter_array = $this::NO_SESSION_FILTER_ARRAY;
        $result = [];
        $result_item = [];
        $currdate = new \DateTime('Australia/Sydney');
        $children = Child::where('child_status', 1)->get();
        foreach ($children as $child) {
            $jobs = Job::where('parent_id', $child->parent_id)->where('child_id', $child->id)->where('job_status', 1)->where('job_type', '!=', 'creative')->where('accepted_by', '!=', '')->whereNotNull('accepted_by')->get();
            if (!empty($jobs)) {
                foreach ($jobs as $job) {
                    $fupdate = '-';
                    $no_ses = Session::where('child_id', $child->id)->where('parent_id', $child->parent_id)->where('tutor_id', $job->accepted_by)->where('session_is_first', 0)->orderBy('id', 'desc')->first();
                    if (!empty($no_ses)) {
                        if (!in_array($no_ses->session_status, [2, 4, 6])) continue;

                        $connectedTutor = TutorConnected::where('tutor_id', $no_ses->tutor_id)->where('child_id', $no_ses->child_id)->first();
                        if (!empty($connectedTutor)) continue;

                        $result_item['filter'] = $filter_array[1];
                        $followup_check = SessionFilter::where('session_id', $no_ses->id)->first();
                        if (!empty($followup_check)) {
                            if (!empty($followup_check->followup_timestamp)) {
                                $followup_timestamp = $followup_check->followup_timestamp;
                                if (is_numeric($followup_timestamp) && (int)$followup_timestamp == $followup_timestamp) {
                                    $fupdate = date('d/m/Y', $followup_timestamp);  //if followup_timestamp is timestamp. ex:12345667
                                    if (!empty($filter) && (int)$followup_timestamp > $currdate->getTimestamp()) continue;
                                } else { //if followup_timestamp is date string. ex:25/06/2024
                                    $followup_date = \DateTime::createFromFormat('d/m/Y', $followup_timestamp);
                                    $fupdate = $followup_date->format('d/m/Y');
                                    if (!empty($filter) && $followup_date->getTimestamp() > $currdate->getTimestamp()) continue;
                                }
                            }
                            if (!empty($filter)) $result_item['filter'] = $filter_array[$followup_check->filter];
                            else {
                                if (!empty($filter_array[$followup_check->filter])) $result_item['filter'] = $filter_array[$followup_check->filter];
                                else $result_item['filter'] = 'All';
                            }
                        }

                        if ($no_ses->session_is_first == 1) $result_item['session_type'] = 'First';
                        else {
                            $prev_ses = $no_ses->prev_session;
                            if (!empty($prev_ses) && $prev_ses->session_is_first == 1) $result_item['session_type'] = 'Second';
                            else $result_item['session_type'] = 'Regular';
                        }

                        $str = '';
                        if ($no_ses->session_status == 1) $str = 'Unconfirmed';
                        else if ($no_ses->session_status == 2) $str = 'Confirmed';
                        else if ($no_ses->session_status == 3) $str = 'Scheduled';
                        else {
                            if ($no_ses->session_length > 0) $str = 'Confirmed';
                            else $str = 'Unconfirmed';
                        }
                        $result_item['session_status'] = $str;

                        $result_item['id'] = $no_ses->id;
                        $result_item['followup_date'] = $fupdate;
                        $result_item['session_date'] = $no_ses->session_date ?? '-';
                        $result_item['session_last_changed'] = $no_ses->session_last_changed ?? '-';
                        $result_item['session_next_session_tutor_date'] = $no_ses->session_next_session_tutor_date ?? '-';
                        $result_item['session_subject'] = $no_ses->session_subject ?? '-';
                        $result_item['session_length'] = $no_ses->session_length ?? '-';
                        $result_item['session_charge_time'] = $no_ses->session_charge_time ?? '-';
                        $result_item['child_name'] = $no_ses->child->child_name ?? '-';
                        $result_item['child_year'] = $no_ses->child->child_year ?? '-';
                        $result_item['child_school'] = $no_ses->child->child_school ?? '-';
                        $result_item['parent_id'] = $no_ses->parent->id ?? '-';
                        $result_item['parent_name'] = $no_ses->parent->parent_first_name . ' ' . $no_ses->parent->parent_last_name  ?? '-';
                        $result_item['parent_email'] = $no_ses->parent->parent_email ?? '-';
                        $result_item['parent_phone'] = $no_ses->parent->parent_phone ?? '-';
                        $result_item['parent_address'] = $no_ses->parent->parent_address ?? '-';
                        $result_item['parent_suburb'] = $no_ses->parent->parent_suburb ?? '-';
                        $result_item['parent_postcode'] = $no_ses->parent->parent_postcode ?? '-';
                        $result_item['stripe_customer_id'] = $no_ses->parent->stripe_customer_id ?? '-';
                        $result_item['tutor_id'] = $no_ses->tutor->id ?? '-';
                        $result_item['tutor_name'] = $no_ses->tutor->tutor_name ?? '-';
                        $result_item['tutor_email'] = $no_ses->tutor->user->email ?? '-';
                        $result_item['tutor_phone'] = $no_ses->tutor->tutor_phone ?? '-';
                        $result_item['tutor_address'] = $no_ses->tutor->address ?? '-';
                        $result_item['tutor_suburb'] = $no_ses->tutor->suburb ?? '-';
                        $result_item['tutor_postcode'] = $no_ses->tutor->postcode ?? '-';
                        $result_item['tutor_stripe_user_id'] = $no_ses->tutor->tutor_stripe_user_id ?? '-';

                        $result[] = $result_item;
                    }
                }
            }
        }
        return collect($result);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('filter')
            ->add('session_status')
            ->add('tutor_id')
            ->add('parent_id')
            ->add('followup_date')
            ->add('session_date')
            ->add('session_last_changed')
            ->add('child_name')
            ->add('child_year')
            ->add('child_school')
            ->add('parent_name')
            ->add('parent_email')
            ->add('parent_phone')
            ->add('parent_address')
            ->add('parent_suburb')
            ->add('parent_postcode')
            ->add('stripe_customer_id')
            ->add('tutor_name')
            ->add('tutor_email')
            ->add('tutor_phone')
            ->add('tutor_address')
            ->add('tutor_suburb')
            ->add('tutor_postcode')
            ->add('tutor_stripe_user_id')
            ->add('session_type')
            ->add('session_subject')
            ->add('session_length')
            ->add('session_charge_time')
            ->add('session', function ($row) {
                $session = Session::with(['child', 'parent', 'tutor', 'history', 'prev_session'])->find($row->id);
                return $session;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Filter')->field('filter')->sortable(),
            Column::add()->title('Session status')->field('session_status')->sortable(),
            Column::add()->title('Tutor ID')->field('tutor_id')->sortable(),
            Column::add()->title('Parent ID')->field('parent_id')->sortable(),
            Column::add()->title('Follow up date')->field('followup_date')->sortable(),
            Column::add()->title('Session date')->field('session_date')->sortable(),
            Column::add()->title('Confirmed on')->field('session_last_changed')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Student grade')->field('child_year')->sortable(),
            Column::add()->title('Student school')->field('child_school')->sortable(),
            Column::add()->title('Parent name')->field('parent_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Parent phone')->field('parent_phone')->sortable()->searchable(),
            Column::add()->title('Parent address')->field('parent_address')->sortable(),
            Column::add()->title('Parent suburb')->field('parent_suburb')->sortable(),
            Column::add()->title('Parent postcode')->field('parent_postcode')->sortable(),
            Column::add()->title('Parent stripe ID')->field('stripe_customer_id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Tutor address')->field('tutor_address')->sortable(),
            Column::add()->title('Tutor suburb')->field('tutor_suburb')->sortable(),
            Column::add()->title('Tutor postcode')->field('tutor_postcode')->sortable(),
            Column::add()->title('Tutor suggested date')->field('tutor_stripe_user_id')->sortable(),
            Column::add()->title('Session type')->field('session_type')->sortable(),
            Column::add()->title('Session subject')->field('session_subject')->sortable(),
            Column::add()->title('Session length')->field('session_length')->sortable(),
            Column::add()->title('Paid on')->field('session_charge_time')->sortable(),
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

    public function filters(): array
    {
        return [
            Filter::inputText('child_name'),
            Filter::inputText('parent_name'),
            Filter::inputText('parent_email'),
            Filter::inputText('parent_phone'),
            Filter::inputText('tutor_name'),
            Filter::inputText('tutor_email'),
            Filter::inputText('tutor_phone'),
        ];
    }

    public function addComment($ses_id, $comment)
    {
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
        }
    }

    /**
     * @param $followup_status : 1-6 or null, $followup_date : '25/05/2024'
     */
    public function changeStatus($ses_id, $followup_status, $followup_date)
    {
        try {
            $session = Session::find($ses_id);
            $session_filter = SessionFilter::updateOrcreate([
                'session_id' => $session->id,
                'tutor_id' => $session->tutor_id,
                'parent_id' => $session->parent_id,
                'child_id' => $session->child_id
            ], [
                'followup_timestamp' => $followup_date,
                'filter' => $followup_status
            ]);

            $filter_array = $this::NO_SESSION_FILTER_ARRAY;
            $status = !empty($followup_status) ? $filter_array[$followup_status] : 'All';
            $this->addSessionHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Changed not continueing session status to '" . $status . "'and added follow up time: " . $followup_date,
                'session_id' => $ses_id
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'Status changed!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function makeStudentToInactive($child_id, $delete_student_reason, $followup, $disable_future_follow_up_reason) {
        try {
            if (empty($delete_student_reason)) throw new \Exception('Input the reason.');
            if (empty($followup)) throw new \Exception('Select follow up.');
            
            if (!empty($followup)) {
                $this->makeStudentInactive($child_id, $delete_student_reason, $followup, $disable_future_follow_up_reason);
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The student is now inactive!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
