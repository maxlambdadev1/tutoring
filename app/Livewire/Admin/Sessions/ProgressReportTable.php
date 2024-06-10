<?php

namespace App\Livewire\Admin\Sessions;

use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use App\Models\TutorReview;
use App\Models\SessionProgressReport;
use App\Models\User;
use App\Trait\Mailable;
use App\Trait\Sessionable;
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


class ProgressReportTable extends PowerGridComponent
{

    use Functions, Sessionable, Mailable;
    
    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.progress-report-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Builder
    {
        $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
        $query =  SessionProgressReport::query()
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_session_progress_report.child_id', '=', 'alchemy_children.id');
            })
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_session_progress_report.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_session_progress_report.tutor_id', '=', 'tutors.id');
            })
            ->whereNotNull('submitted_on')
            ->whereRaw("TIMESTAMPDIFF(MONTH, STR_TO_DATE(date_lastupdated, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $today->format('d/m/Y H:i'). "', '%d/%m/%Y %H:%i')) <= 12");

        return $query->select('alchemy_session_progress_report.*');
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('child_name', fn ($row) => $row->child->child_name ?? '-')
            ->add('parent_first_name', fn ($row) =>  $row->parent->parent_first_name . ' ' . $row->parent->parent_last_name ?? '-')
            ->add('parent_email', fn ($row) => $row->parent->parent_email ?? '-')
            ->add('parent_phone', fn ($row) => $row->parent->parent_phone ?? '-')
            ->add('tutor_name', fn ($row) =>  $row->tutor->tutor_name ?? '-')
            ->add('tutor_email', fn ($row) =>  $row->tutor->tutor_email ?? '-')
            ->add('tutor_phone', fn ($row) =>  $row->tutor->tutor_phone ?? '-')
            ->add('submitted_on', fn ($row) => $row->submitted_on ?? '-')
            ->add('session_count', fn ($row) => $row->session_count ?? '-')
            ->add('feedback_received', function ($row) {
                $result = 'No';
                if ($row->review_reminder == 1) {
                    $feedback_row = TutorReview::where('progress_report_id', $row->id)->first();
                    if (empty($feedback_row)) {
                        $feedback_row = TutorReview::where('tutor_id', $row->tutor_id)->where('parent_id', $row->parent_id)->where('child_id', $row->child_id)->first();
                    }
                    if (!empty($feedback_row)) $result = 'Yes';
                }

                return $result;
            })
            ->add('tutor_review', function ($row) {
                $feedback_row = null;
                if ($row->review_reminder == 1) {
                    $feedback_row = TutorReview::where('progress_report_id', $row->id)->first();
                    if (empty($feedback_row)) {
                        $feedback_row = TutorReview::where('tutor_id', $row->tutor_id)->where('parent_id', $row->parent_id)->where('child_id', $row->child_id)->first();
                    }
                }
                return $feedback_row;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Parent email')->field('parent_email')->sortable()->searchable(),
            Column::add()->title('Parent phone')->field('parent_phone')->sortable()->searchable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Submitted on')->field('submitted_on')->sortable(),
            Column::add()->title('Session count')->field('session_count')->sortable(),
            Column::add()->title('Feedback received')->field('feedback_received'),
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

    public function makeSessionNotContinuing1($ses_id) {
        try {
            $this->makeSessionNotContinuing($ses_id);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The session was transfered to not continuing'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function sendReportEmail($report_id) {
        try {
            $report = SessionProgressReport::find($report_id);
            $report->update(['review_reminder' => 1]);

            $child = $report->child;
            $parent = $report->parent;

            $params = [
                'q1' => $report->q1,
                'q2' => $report->q2,
                'q3' => $report->q3,
                'q4' => $report->q4,
                'studentfirstname' => $child->first_name,
                'studentname' => $child->child_name,
                'parentfirstname' => $parent->parent_first_name,
                'email' => $parent->parent_email
            ];
            $this->sendEmail($parent->parent_email, 'parent-progress-report-email', $params);

            $this->addParentHistory([
                'parent_id' => $parent->id,
                'author' => User::find(auth()->user()->id)->admin->admin_name,
                'comment' => "The email for the " . $report->session_count . " sessions progress report was sent to the parent"
            ]);

            $this->addSessionHistory([
                'session_id' => $report->last_session,
                'author' => User::find(auth()->user()->id)->admin->admin_name,
                'comment' => "The email for the " . $report->session_count . " sessions progress report was sent to the parent"
            ]);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => 'The email was sent to the parent.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
