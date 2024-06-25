<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AlchemyParent;
use App\Models\Child;
use App\Models\HolidayReplacement;
use App\Models\HolidayReplacementHistory;
use App\Models\ReplacementTutor;
use App\Models\Session;
use App\Trait\Functions;

trait WithParents {
    
    use Functions;

    /**
     * @param $child_id, $delete_student_reason : string, $followup : 1-4, $disable_future_follow_up_reason : string, $is_holiday_student : false or true
     */
    public function makeStudentInactive($child_id, $delete_student_reason, $followup, $disable_future_follow_up_reason, $is_holiday_student = false) {
        try {
            if (empty($followup)) throw new \Exception('Select the follow up');

            $child = Child::find($child_id);

            $child->update([
                'child_status' => 0,
                'follow_up' => $followup,
                'no_follow_up_reason' => $disable_future_follow_up_reason,
            ]);

            if (!empty($is_holiday_student)) {
                $holiday_replacement = HolidayReplacement::where('child_id', $child_id);
                if (!empty($holiday_replacement)) {
                    ReplacementTutor::find($holiday_replacement->replacement_id)->delete();
                    HolidayReplacementHistory::where('holiday_id', $holiday_replacement->id)->delete();
                    $holiday_replacement->delete();
                }
            }

            $this->addStudentHistory([
                'child_id' => $child->id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => "Sent student to inactive. Reason: " .$delete_student_reason
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}