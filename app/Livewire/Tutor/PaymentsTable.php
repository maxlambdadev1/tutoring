<?php

namespace App\Livewire\Tutor;

use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Job;
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
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;


class PaymentsTable extends PowerGridComponent
{
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public $google_ads = 0;

    public function setUp(): array
    {
        $is_phone = (new Agent())->isPhone();

        $arr = [
            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
        if ($is_phone) $arr[] = Detail::make()
            ->view('livewire.tutor.components.payment-detail')
            ->showCollapseIcon();

        return $arr;
    }

    public function datasource(): ?Builder
    {
        $tutor = auth()->user()->tutor;

        $query =  Session::query()
            ->leftJoin('alchemy_children', function ($child) {
                $child->on('alchemy_sessions.child_id', '=', 'alchemy_children.id');
            })
            ->leftJoin('tutors', function ($tutor) {
                $tutor->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
            })
            ->where('session_status', 2)
            ->where('tutor_id', $tutor->id);

        return $query->select('alchemy_sessions.*');
    }

    public function relationSearch(): array
    {
        return [
            'child' => [
                'child_name'
            ]
        ];
    }


    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('alchemy_sessions.id', fn ($ses) => $ses->id)
            ->add('child_name', fn ($ses) => $ses->child->child_name ?? '-')
            ->add('session_date', fn ($ses) => $ses->session_date . ' ' . $ses->session_time)
            ->add('session_charge_status', function ($ses) {
                if (stripos($ses->session_charge_status, 'scheduled') !== false) {
                    $schedule_date = date('d/m/Y H:i', strtotime(str_replace('/', '-', $ses->session_last_changed)) + 60 * 60 * 24);
                    return "Payment scheduled for " . $schedule_date;
                } else if (stripos($ses->session_charge_status, 'paid') !== false) {
                    return 'Paid';
                } else if (stripos($ses->session_charge_status, 'failed') !== false) {
                    return 'Payment failed - we are seeking new payment details from the parent';
                } else if (stripos($ses->session_charge_status, 'awaiting') !== false) {
                    return 'Awaiting payment information from the parent';
                } else if (stripos($ses->session_charge_status, 'manual') !== false) {
                    return 'Manual payment required';
                } else if (stripos($ses->session_charge_status, 'not') !== false) {
                    return 'Not continuing after first lesson - no charge for the lesson';
                } else return '-';
            })
            ->add('session_length', function ($ses) {
                $length = $ses->session_length;
                $job = Job::where('session_id', $ses->id)->where('job_type', 'creative')->first();
                if (!empty($job)) $length = 1;
                return $length . ' hour(s)';
            })
            ->add('type_id', fn ($ses) =>  $ses->type_id ==  1 ? 'Face To Face' : 'Online')
            ->add('session_last_changed', fn ($ses) =>  $ses->session_last_changed)
            ->add('session_tutor_price', fn ($ses) =>  '$' . $ses->session_tutor_price * $ses->session_length ?? '-')
            ->add('session_charge_time', function ($ses) {
                $time = '-';
                if (stripos($ses->session_charge_status, 'paid') !== false) {
                    if (!empty($ses->session_transfer_time)) $time = $ses->session_transfer_time;
                    else $time = $ses->session_charge_time;
                }
                return $time;
            })
            ->add('pdf', function ($ses) {
                $pdf = '-';
                $path = 'invoice/' . base64_encode(str_replace(' ', '_', $ses->tutor->tutor_name) . '_' . $ses->child->child_name . '_' . $ses->session_date . '_' . $ses->session_time . '_tax_invoice') . '.pdf';
                if (Storage::disk('public')->exists($path)) {
                    $pdf = $path;
                }
                return $pdf;
            });
    }

    public function columns(): array
    {
        $is_phone = (new Agent())->isPhone();

        if (!$is_phone) $columns = [
            Column::add()->title('ID')->field('alchemy_sessions.id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
            Column::add()->title('Lesson date and time')->field('session_date'),
            Column::add()->title('Status')->field('session_charge_status')->sortable(),
            Column::add()->title('Lesson length')->field('session_length')->sortable(),
            Column::add()->title('Lesson Type')->field('type_id')->sortable(),
            Column::add()->title('Confirmed on')->field('session_last_changed'),
            Column::add()->title('Payment amount')->field('session_tutor_price'),
            Column::add()->title('Date and time paid')->field('session_charge_time'),
            Column::add()->title('View invoice')->field('pdf'),
        ];
        else $columns = [
            Column::add()->title('ID')->field('alchemy_sessions.id')->sortable(),
            Column::add()->title('Student name')->field('child_name')->sortable()->searchable(),
        ];

        return $columns;
    }
}
