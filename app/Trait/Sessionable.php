<?php

namespace App\Trait;

use App\Models\ConversionTarget;
use App\Models\FirstSessionTarget;
use App\Models\TutorFirstSession;
use App\Models\Session;
use App\Trait\Functions;

trait Sessionable {
    
    use Functions;

    public function checkTutorFirstSession($tutor_id) {
        $sessions_length = Session::where('tutor_id', $tutor_id)->count();
        if ($sessions_length < 1) {
            TutorFirstSession::create([
                'tutor_id' => $tutor_id,
                'status' => 1,
                'date_created' => date('d/m/Y H:i'),
                'date_last_update' => date('d/m/Y H:i')
            ]);

            $param = [
                'tutor_id' => $tutor_id,
                'comment' => "Added to first session list. Set status 'Scheduling call'"
            ];
            $this->addTutorHistory($param);
        }
    }

    public function addConversionTarget($job_id, $session_id, $converted_by = 'admin') {
        $datetime = new \DateTime('Australia/Sydney');
        ConversionTarget::create([
            'job_id' => $job_id,
            'session_id' => $session_id,
            'conversion_date' => $datetime->format('d/m/Y'),
            'converted_by' => $converted_by
        ]);
    }

    public function addFirstSessionTarget($session_id) {
        $datetime = new \DateTime('Australia/Sydney');
        FirstSessionTarget::create([
            'session_id' => $session_id,
            'session_date' => $datetime->format('d/m/Y'),
        ]);
    }

}