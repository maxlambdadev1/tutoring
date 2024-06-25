<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\AlchemyParent;
use App\Models\ReplacementTutor;
use App\Models\HolidayReplacement;
use App\Models\HolidayStudent;



trait WithEndOfHoliday
{
    /**
     * move holiday tutor to replacement list
     */
    public function moveHolidayReplacement($tutor_id, $child_id)
    {
        try {
            $child = Child::find($child_id);
            $tutor = Tutor::find($tutor_id);
            
            $last_session = Session::where('tutor_id', $tutor->id)->where('child', $child->id)->orderBy('id', 'desc')->first();
            $parent = $child->parent;

            $check_replacement = ReplacementTutor::where('tutor_id', $tutor->id)->where('child_id', $child->id)->count();
            if (!empty($check_replacement)) throw new \Exception('The tutor has already been replaced.');

            $inserted_replacement_tutor = ReplacementTutor::create([
                'tutor_id' => $tutor->id,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'last_session' => $last_session->id ?? null,
                'replacement_status' => 2,
                'tutor_last_session' => $last_session->session_date ?? null,
                'date_added' => (new \DateTime('now'))->format('d/m/Y H:i'),
                'last_modified' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $tutor_ukey = base64_encode(serialize([
                'type' => 'replacement-tutor',
                'replacement_id' => $inserted_replacement_tutor->id,
                'tutor_id' => $tutor->id,
                'tutor_name' => $tutor->tutor_name,
                'child_name' => $child->child_name,
                'child_first_name' => $child->first_name,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'parent_name' => $parent->parent_name
            ]));                            
            $parent_ukey = base64_encode(serialize([
                'type' => 'replacement-parent',
                'replacement_id' => $inserted_replacement_tutor->id,
                'tutor_id' => $tutor->id,
                'tutor_name' => $tutor->tutor_name,
                'child_name' => $child->child_name,
                'child_first_name' => $child->first_name,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'parent_name' => $parent->parent_name
            ]));
            $inserted_replacement_tutor->update([
                'tutor_link' => $tutor_ukey,
                'parent_link' => $parent_ukey
            ]);

            $holiday_replacement = HolidayReplacement::create([
                'child_id' => $child->id,
                'year' => date('Y'),
                'replacement_id' => $inserted_replacement_tutor->id,
                'date_created' => date('d/m/Y H:i'),
                'date_last_modified' => date('d/m/Y H:i'),
            ]);
            $this->addHolidayReplacementHistory([
                'holiday_id' => $holiday_replacement->id,
                'author' => auth()->user()->admin->admin_name,
                'comment'=> 'Added ' . $child->child_name . ' to replacement for end of holidays in ' . date('Y')
            ]);

            $holiday_student = HolidayStudent::where('child_id', $child->id)->where('last_tutor', $tutor->id)->where('year', date('Y'))->first();
            if (!empty($holiday_student)) {
                $holiday_student->update(['status' => 5]);
                $this->addHolidayStudentHistory([
                    'holiday_id' => $holiday_student->id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Changed status for ' . $child->child_name . ' to replacement for end of holidays in ' . date('Y')
                ]);
            }

        }  catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
