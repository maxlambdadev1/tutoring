<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Child;
use App\Models\HolidayReachParent;
use App\Models\HolidayReplacement;
use App\Models\HolidayReplacementHistory;
use App\Models\HolidayStudent;
use App\Models\ReplacementTutor;
use App\Trait\Functions;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ThankyouParent extends Component
{
    use Functions;

    public function mount()
    {
        $holiday_replacement_id = null;
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $holiday_replacement_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($email)) {
                        $secret_origin = sha1($email . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            if (!empty($holiday_replacement_id)) {
                                $flag = true;
                            }
                        }
                    }
                }
            }
        }
        // $exp = [];
        // $holiday_replacement_id = 123;
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        else {
            if (empty($exp[2])) $this->newYearRemoveReplacement($holiday_replacement_id);
            else $this->newYearRemoveReachParent($holiday_replacement_id);
        }

    }

    private function newYearRemoveReplacement($holiday_replacement_id)
    {
        $holiday_replacement = HolidayReplacement::find($holiday_replacement_id);
        if (!empty($holiday_replacement)) {
            $child = Child::find($holiday_replacement->child_id);
            $child->update([
                'child_status' => 0,
                'follow_up' => 1,
                'no_follow_up' => ''
            ]);

            HolidayStudent::where('child_id', $child->id)->update(['status' => 8]); //inactivated student

            $this->addStudentHistory([
                'child_id' => $child->id,
                'comment' => 'Sent student to inactive. Reason: Parent has chosen not to continue with a replacement tutor in end of holidays process'
            ]);

            ReplacementTutor::where('id', $holiday_replacement_id)->delete();
            HolidayReplacement::where('id', $holiday_replacement_id)->delete();
            HolidayReplacementHistory::where('id', $holiday_replacement_id)->delete();
        }
    }

    private function newYearRemoveReachParent($holiday_replacement_id)
    {
        $holiday_row = HolidayStudent::find($holiday_replacement_id);
        if (!empty($holiday_row) && $holiday_row->status != 8) {
            HolidayReachParent::where('holiday_id', $holiday_replacement_id)->delete();
            $holiday_row->update(['status' => 8]);
            $child = Child::find($holiday_row->child_id);
            $child->update([
                'child_status' => 0,
                'follow_up' => 1,
                'no_follow_up_reason' => ''
            ]);
            $this->addStudentHistory([
                'child_id' => $child->id,
                'comment' => 'Sent student to inactive. Reason: Parent has chosen not to continue with alchemy in end of holidays process'
            ]);
        }
    }


    public function render()
    {
        return view('livewire.tutor.pages.thankyou-parent');
    }
}
