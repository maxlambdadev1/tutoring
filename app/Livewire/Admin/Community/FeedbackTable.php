<?php

namespace App\Livewire\Admin\Community;

use App\Models\AlchemyParent;
use App\Models\NewsNotification;
use App\Models\NewsPost;
use Illuminate\Support\Facades\DB;
use App\Models\TutorReview;
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
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\Mailable;


class FeedbackTable extends PowerGridComponent
{
    use  PriceCalculatable, Sessionable, Mailable;

    public $status;
    public $thirdparty = false;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('livewire.admin.components.feedback-detail')
                ->params(['status' => $this->status])
                ->showCollapseIcon()

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  TutorReview::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_tutor_review.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_tutor_review.tutor_id', '=', 'tutors.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_tutor_review.child_id', '=', 'alchemy_children.id');
            })
            ->where('hidden', 0)
            ->whereNot('type', 1);

        if ($this->status == 'pending') $query = $query->where('reject', 0)->where('approved', 0);
        else if ($this->status == 'approved') $query = $query->where('approved', 1)->whereNot('type', 2);
        else if ($this->status == 'totm') $query = $query->where('type', 2)->where('approved', 1);
        else if ($this->status == 'rejected') $query = $query->where('reject', 1);

        return    $query->select('alchemy_tutor_review.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_first_name',
                'parent_last_name',
            ],
            'tutor' => [
                'tutor_name'
            ],
            'child' => [
                'child_name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('tutor_name', fn ($item) => $item->tutor->tutor_name ?? '-')
            ->add('parent_first_name', fn ($item) => $item->parent->parent_first_name . ' ' . $item->parent->parent_last_name)
            ->add('child_name', fn ($item) => $item->child->child_name ?? '-')
            ->add('completed_lesson', function ($item) {
                return Session::where('tutor_id', $item->tutor_id)->where(function ($query) {
                    $query->where('session_status', 2)->orWhere('session_status', 4);
                })->count();
            })
            ->add('type', function ($item) {
                $str = 'Regular';
                if ($item->type == 1) $str = '10 lessons';
                else if ($item->type == 2) $str = 'TOTM';
                return $str;
            })
            ->add('rating', fn ($item) => $item->rating ?? '-')
            ->add('date_lastupdated', fn ($item) => $item->date_lastupdated ?? '-');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Parent name')->field('parent_first_name')->sortable()->searchable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Completed lessons')->field('completed_lesson'),
            Column::add()->title('Feedback Type')->field('type'),
            Column::add()->title('Rating')->field('rating'),
            Column::add()->title('Created Date')->field('date_lastupdated'),
            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [
            Button::add('detail')
                ->slot('Detail')
                ->class('btn btn-outline-info waves-effect waves-light btn-sm')
                ->toggleDetail(),
        ];
    }

    /** table actions */
    public function tutorReviewUpdate($id, $rating_comment)
    {
        try {
            if (!empty($id) && !empty($rating_comment)) {
                $tutor_review = TutorReview::find($id);
                $tutor_review->update([
                    'rating_comment' => $rating_comment
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'The comment was updated successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function tutorReviewApprove($id)
    {
        try {
            if (!empty($id)) {
                $tutor_review = TutorReview::find($id);
                if ($tutor_review->approved == 1) throw new \Exception('It was already approved');

                $parent = $tutor_review->parent;

                $tutor_review->update([
                    'approved' => 1,
                    'reject' => 0
                ]);

                if ($tutor_review->type != 2) {
                    $post = NewsPost::create([
                        'user_id' => auth()->user()->id,
                        'content' => "<blockquote><q>" . $tutor_review->rating_comment . "</q><br><i>--&nbsp;" . $parent->parent_first_name . " " . $parent->parent_last_name . "</i></blockquote>",
                        'tagged_tutor' => $tutor_review->tutor_id,
                        'allow' => 1,
                        'type' => 1
                    ]);
                }

                $tutor = $tutor_review->tutor;
                if (!empty($tutor)) {
                    if ($tutor_review->type != 2) {
                        NewsNotification::create([
                            'send_id' => auth()->user()->id,
                            'receive_id' => $tutor->user_id,
                            'notifiable_id' => $post->id ?? '',
                            'type' => 'post'
                        ]);
                    }

                    $params = [
                        'email' => $tutor->tutor_email,
                        'tutorfirstname' => $tutor->first_name,
                        'parentfirstname' => $parent->parent_first_name,
                        'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                        'feedback' => $tutor_review->rating_comment
                    ];
                    if ($tutor_review->type == 2) $this->sendEmail($tutor->tutor_email, 'tutor-totm-nomination', $params);
                    else $this->sendEmail($tutor->tutor_email, 'tutor-feedback-email', $params);

                    $this->sendEmail($parent->parent_email, 'parent-review-request-email', $params);
                }

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'The comment was updated successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function tutorReviewEmail($id)
    {
        try {
            if (!empty($id)) {
                $tutor_review = TutorReview::find($id);

                $parent = $tutor_review->parent;
                $tutor = $tutor_review->tutor;
                if (!empty($tutor)) {
                    $params = [
                        'email' => $tutor->tutor_email,
                        'tutorfirstname' => $tutor->first_name,
                        'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                        'feedback' => $tutor_review->rating_comment
                    ]; 

                    $this->sendEmail($tutor->tutor_email, 'tutor-feedback-email', $params);
                }

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'Email was sent successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function tutorReviewReject($id)
    {
        try {
            if (!empty($id)) {
                $tutor_review = TutorReview::find($id);

                $tutor_review->update([
                    'reject' => 1
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'It was rejected successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function tutorReviewHide($id)
    {
        try {
            if (!empty($id)) {
                $tutor_review = TutorReview::find($id);

                $tutor_review->update([
                    'hidden' => 1
                ]);

                $this->dispatch('showToastrMessage', [
                    'status' => 'success',
                    'message' => 'It was hidden successfully.'
                ]);
            } else throw new \Exception("Invalid parameters.");
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
