<?php

namespace App\Livewire\Admin\Sessions;

use Illuminate\Support\Facades\DB;
use App\Models\Session;
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


class FirstSessionsTable extends PowerGridComponent
{
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    
    public $thirdparty_org_id;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

        ];
    }

    public function datasource(): ?Builder
    {
        $query =  Session::query()
            ->leftJoin('alchemy_parent', function ($parent) {
                $parent->on('alchemy_sessions.parent_id', '=', 'alchemy_parent.id');
            })
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            })
            ->leftJoin('users', function ($user) {
                $user->on('tutors.user_id', '=', 'users.id');
            })
            ->where('session_status', '!=', '6');

        if (!empty($this->thirdparty_org_id)) $query = $query->where('thirdparty_org_id', '=', $this->thirdparty_org_id);

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'parent' => [
                'parent_email',
                'parent_phone',
                'parent_first_name',
                'parent_last_name',
            ],
            'child' => [
                'child_name'
            ],
            'tutor' => [
                'tutor_name',
                'tutor_phone',
                'users' => [
                    'email'
                ]
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('session_status', function ($ses) {
                $str = '';
                if ($ses->session_status == 1) $str = 'Unconfirmed';
                else if ($ses->session_status == 2) $str = 'Confirmed';
                else if ($ses->session_status == 3) $str = 'Scheduled';
                else {
                    if ($ses->session_length > 0) $str = 'Confirmed';
                    else $str = 'Unconfirmed';
                }
                return $str;
            })
            ->add('tutor_id', fn ($ses) => $ses->tutor_id)
            ->add('parent_id', fn ($ses) => $ses->parent_id)
            ->add('session_date', fn ($ses) => $ses->session_date)
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('child_year', fn ($ses) =>  $ses->child->child_year ?? '-')
            ->add('child_school', fn ($ses) =>  $ses->child->child_school ?? '-')
            ->add('parent_first_name', fn ($ses) =>  $ses->parent ? $ses->parent->parent_first_name . ' ' . $ses->parent->parent_last_name : '-')
            ->add('parent_email', fn ($ses) =>  $ses->parent->parent_email ?? '-')
            ->add('parent_phone', fn ($ses) =>  $ses->parent->parent_phone ?? '-')
            ->add('parent_address', fn ($ses) =>  $ses->parent->parent_address ?? '-')
            ->add('parent_suburb', fn ($ses) =>  $ses->parent->parent_suburb ?? '-')
            ->add('parent_postcode', fn ($ses) =>  $ses->parent->parent_postcode ?? '-')
            ->add('stripe_customer_id', fn ($ses) =>  $ses->parent->stripe_customer_id ?? '-')
            ->add('tutor_name', fn ($ses) =>  $ses->tutor->tutor_name ?? '-')
            ->add('email', fn ($ses) =>  $ses->tutor->user->email ?? '-')
            ->add('tutor_phone', fn ($ses) =>  $ses->tutor->tutor_phone ?? '-')
            ->add('address', fn ($ses) =>  $ses->tutor->address ?? '-')
            ->add('suburb', fn ($ses) =>  $ses->tutor->suburb ?? '-')
            ->add('postcode', fn ($ses) =>  $ses->tutor->postcode ?? '-')
            ->add('tutor_stripe_user_id', fn ($ses) =>  $ses->tutor->tutor_stripe_user_id ?? '-')
            ->add('session_next_session_tutor_date', fn ($ses) =>  $ses->session_next_session_tutor_date ?? '-')
            ->add('session_type', function ($ses) {
                if ($ses->session_is_first == 1) return 'First';
                else {
                    $prev_ses = $ses->prev_session;
                    if (!empty($prev_ses) && $prev_ses->session_is_first == 1) return 'Second';
                    else return 'Regular';
                }
            })
            ->add('session_subject', fn ($ses) =>  $ses->session_subject)
            ->add('session_length', fn ($ses) =>  $ses->session_length)
            ->add('session_price', fn ($ses) =>  $ses->session_price)
            ->add('session_tutor_price', fn ($ses) =>  $ses->session_tutor_price)
            ->add('session_last_changed', fn ($ses) =>  $ses->session_last_changed)
            ->add('session_reason', fn ($ses) =>  $ses->session_reason ?? '-')
            ->add('session_charge_time', fn ($ses) =>  $ses->session_charge_time ?? '-')
            ->add('session_feedback', fn ($ses) =>  $ses->session_feedback ?? '-')
            ->add('session_charge_status', fn ($ses) =>  $ses->session_charge_status ?? '-')
            ->add('type_id', fn ($ses) =>  $ses->type_id =  1 ? 'Face To Face' : 'Online');
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Session status')->field('session_status'),
            Column::add()->title('Tutor ID')->field('tutor_id')->sortable(),
            Column::add()->title('Parent ID')->field('parent_id')->sortable(),
            Column::add()->title('Session Date')->field('session_date')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Student Grade')->field('child_year')->sortable(),
            Column::add()->title('Student school')->field('child_school')->sortable(),
            Column::add()->title('Parent name')->field('parent_first_name')->searchable()->sortable(),
            Column::add()->title('Parent email')->field('parent_email')->searchable()->sortable(),
            Column::add()->title('Parent phone')->field('parent_phone')->searchable()->sortable(),
            Column::add()->title('Parent address')->field('parent_address')->sortable(),
            Column::add()->title('Parent suburb')->field('parent_suburb')->sortable(),
            Column::add()->title('Parent postcode')->field('parent_postcode')->sortable(),
            Column::add()->title('Parent postcode')->field('parent_postcode')->sortable(),
            Column::add()->title('Parent stripe ID')->field('stripe_customer_id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('email')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Tutor address')->field('address')->sortable(),
            Column::add()->title('Tutor suburb')->field('suburb')->sortable(),
            Column::add()->title('Tutor postcode')->field('postcode')->sortable(),
            Column::add()->title('Tutor stripe ID')->field('tutor_stripe_user_id')->sortable(),
            Column::add()->title('Tutor Suggested date')->field('session_next_session_tutor_date')->sortable(),
            Column::add()->title('Session type')->field('session_type'),
            Column::add()->title('Subject')->field('session_subject')->sortable(),
            Column::add()->title('Length')->field('session_length')->sortable(),
            Column::add()->title('Session price')->field('session_price')->sortable(),
            Column::add()->title('Tutor price')->field('session_tutor_price')->sortable(),
            Column::add()->title('Confirmed on')->field('session_last_changed')->sortable(),
            Column::add()->title('Reason')->field('session_reason')->sortable(),
            Column::add()->title('Paid on')->field('session_charge_time')->sortable(),
            Column::add()->title('Feedback')->field('session_feedback')->sortable(),
            Column::add()->title('Payment status')->field('session_charge_status')->sortable(),
            Column::add()->title('Lesson type')->field('type_id')->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('child_name'),
            Filter::inputText('parent_first_name'),
            Filter::inputText('parent_email'),
            Filter::inputText('parent_phone'),
            Filter::inputText('tutor_name'),
            Filter::inputText('email'),
            Filter::inputText('tutor_phone'),
        ];
    }
}
