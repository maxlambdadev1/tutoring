<?php

namespace App\Livewire\Admin\Tutors;

use App\Trait\WithTutors;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\TutorApplication;

#[Layout('admin.layouts.app')]
class TutorApplicationStats extends Component
{
    use WithTutors;

    public function render()
    {
        $data = [
            '1' => 0, 
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            'total' => 0,
        ];

        $query =  TutorApplication::leftJoin('alchemy_tutor_application_status', function ($status) {
            $status->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
        });
        $total = $query->count();

        if ($total > 0) {
            $res = $query->get();
            foreach ($res as $item) {
                if ($item->application_status == 1) $data['1']++;
                else if ($item->application_status == 2) $data['2']++;
                else if ($item->application_status == 3) $data['3']++;
                else if ($item->application_status == 4) $data['4']++;
                else if ($item->application_status == 5) $data['5']++;
                else if ($item->application_status == 6) $data['6']++;
                else if ($item->application_status == 7) $data['7']++;
                else if ($item->application_status == 8) $data['8']++;
                else if ($item->application_status == 9) $data['9']++;
            }
        }

        $status = $this::APPLICATION_STATUS;
        $data['total'] = $total;

        return view('livewire.admin.tutors.tutor-application-stats', compact('data', 'status'));
    }
}
