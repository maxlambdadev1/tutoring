<?php

namespace App\Livewire\Admin\Wwcc;

use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Trait\Functions;
use App\Trait\WithTutors;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Query\Builder;
use Livewire\Attributes\On;
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


class ChasingWwccTable extends PowerGridComponent
{

    use Mailable, Functions, WithTutors;
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
                ->view('livewire.admin.components.chasing-wwcc-detail')
                ->showCollapseIcon()
        ];
    }

    public function datasource(): ?Collection
    {
        $result = [];
        $result_item = [];
        $today = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));

        $tutors = Tutor::where('tutor_status', 1)->get();
        foreach ($tutors as $tutor) {
            $wwcc_validate = $tutor->wwcc_validate;
            if (!empty($wwcc_validate)) {
                if (!empty($tutor->wwcc_number)) {
                    if (!empty($tutor->wwcc_expiry)) {
                        $wwcc_expiry = \DateTime::createFromFormat('d/m/Y', trim($tutor->wwcc_expiry));
                        if (time() - $wwcc_expiry->getTimestamp() < 0) continue;
                        else $result_item['reason'] = 'Wrong Action';
                    }
                    else $result_item['reason'] = 'Wrong Action';
                }
                if (empty($tutor->wwcc_application_number)) $result_item['reason'] = 'Wrong Action';
                else {
                    if (empty($tutor->wwcc_number)) $result_item['reason'] = 'Application';
                }
                $result_item['wwcc_application_number'] = $tutor->wwcc_application_number;
                $added_on = new \DateTime('now', new \DateTimeZone('Australia/Sydney'));
                $added_on->setTimestamp($wwcc_validate->timestamp);
                $result_item['added_on'] = $added_on->format('d/m/Y h:i a');
                if ($wwcc_validate->timestamp + 3628800 < time()) $result_item['expiry'] = 'Expired';
                else $result_item['expiry'] = $this->format($wwcc_validate->timestamp + 3628800 - time());
            } else {
                $wwcc = $tutor->wwcc;
                if (!empty($wwcc)) {
                    if (!empty($tutor->wwcc_number)) {
                        if (!empty($tutor->wwcc_expiry)) {
                            $expiry = \DateTime::createFromFormat('d/m/Y', trim($tutor->wwcc_expiry));
                            if (!$expiry) {
                                $result_item['reason'] = 'Wrong WWCC Info';
                                $result_item['expiry'] = '';
                            } else {
                                if ($expiry > $today) {
                                    $interval = $expiry->diff($today);
                                    if ($interval->days <= 31) {
                                        $result_item['reason'] = 'WWCC Expiring';
                                        $result_item['expiry'] = (date('t') - $interval->d).' day(s)';
                                    } else continue;
                                } else {
                                    $result_item['reason'] = 'WWCC Expired';
                                    $result_item['expiry'] = '';
                                }
                            }
                        } else {
                            $result_item['reason'] = 'Wrong WWCC Info';
                            $result_item['expiry'] = '';
                        }
                    } 
                    $result_item['wwcc_application_number'] = $tutor->wwcc_application_number;
                    $result_item['added_on'] = '';
                } else {
                    if (empty($tutor->wwcc_number && empty($tutor->wwcc_application_number))) {
                        $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
                        if ($birth < $today) {
                            $interval = $today->diff($birth);
                            if ($interval->y < 18) {
                                if ($interval->y < 17) continue;
                                if ($interval->y == 17 && $interval->m < 11) continue;
                                else if ($interval->y == 17 && $interval->m == 11) {
                                    $result_item['reason'] = 'Turning 18';
                                    $result_item['expiry'] = (date('t') - $interval->d).' day(s)';
                                }
                            } else if ($interval->y == 18) {
                                $result_item['reason'] = 'Turned 18';
                                $result_item['expiry'] = '';
                            } else {
                                $result_item['reason'] = 'Missing WWCC';
                                $result_item['expiry'] = '';
                            }
                        }
                        $result_item['wwcc_application_number'] = $tutor->wwcc_application_number;
                        $result_item['added_on'] = '';
                    } else continue;
                }
            }
            $result_item['id'] = $tutor->id;
            $result_item['tutor_name'] = $tutor->tutor_name;
            $result_item['tutor_email'] = $tutor->tutor_email;
            $result_item['tutor_phone'] = $tutor->tutor_phone;
            $result_item['state'] = $tutor->state;
            $result_item['birthday'] = $tutor->birthday;
            $result_item['accept_job_status'] = $tutor->accept_job_status;
            
            $result[] = $result_item;
        }
        return collect($result);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tutor_name', fn ($item) =>  $item->tutor_name ?? '-')
            ->add('tutor_phone', fn ($item) =>  $item->tutor_phone ?? '-')
            ->add('tutor_email', fn ($item) =>  $item->tutor_email ?? '-')
            ->add('birthday', fn ($item) =>  $item->birthday ?? '-')
            ->add('state', fn ($item) =>  $item->state ?? '-')
            ->add('wwcc_application_number', fn ($item) =>  $item->wwcc_application_number ?? '-')
            ->add('added_on', fn ($item) =>  $item->added_on ?? '-')
            ->add('reason', fn ($item) =>  $item->reason ?? '-')
            ->add('expiry', fn ($item) =>  $item->expiry ?? '-')
            ->add('tutor', function ($item) {
                $tutor = Tutor::with(['history'])->find($item->id);
                return $tutor;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('ID')->field('id')->sortable(),
            Column::add()->title('Tutor name')->field('tutor_name')->sortable()->searchable(),
            Column::add()->title('Tutor phone')->field('tutor_phone')->sortable()->searchable(),
            Column::add()->title('Tutor email')->field('tutor_email')->sortable()->searchable(),
            Column::add()->title('Tutor birthday')->field('birthday'),
            Column::add()->title('State')->field('state')->sortable(),
            Column::add()->title('WWCC Application')->field('wwcc_application_number')->sortable(),
            Column::add()->title('State')->field('state')->sortable(),
            Column::add()->title('Added on')->field('added_on'),
            Column::add()->title('Reason')->field('reason'),
            Column::add()->title('Expires on')->field('expiry'),
            Column::action('Action'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('block-or-unblock')
                ->slot('Block From Jobs')
                ->class('btn btn-outline-secondary waves-effect waves-light btn-sm')
                ->openModal('block-or-unblock', ['tutor_id' => $row['id']]),
        ];
    }
     
    public function actionRules(): array
    {
        return [
            Rule::button('block-or-unblock')
                ->when(fn ($row) => empty($row->accept_job_status))
                ->slot('Unblock From Jobs')
                ->setAttribute('class', 'btn-outline-primary') 
        ];
    }

    #[On('openModal')]
    public function openModal(string $component, array $arguments)
    {      
        try {
            $tutor_id = $arguments['tutor_id'];  
            $this->blockOrUnblockFromJobs($tutor_id);

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "Job accept status was updated successfully!"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
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

}
