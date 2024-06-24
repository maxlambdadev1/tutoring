<?php

namespace App\Livewire\Admin\EndOfHoliday;

use App\Livewire\Admin\Leads\ReplacementTutor;
use App\Models\HolidayReplacement;
use App\Models\HolidayReplacementHistory;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Models\HolidayStudent;
use App\Models\HolidayStudentHistory;
use App\Models\HolidayTutor;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\HolidayTemp;
use App\Models\HolidayTutorHistory;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

#[Layout('admin.layouts.app')]
class NewYearTutor extends Component
{
    use Functions, Mailable;

    public function render()
    {
        return view('livewire.admin.end-of-holiday.new-year-tutor');
    }

    /**
     * create holidayTutors, holidayStudents for all active tutors and save tutor id to HolidayTemp table
     */
    public function tutorSurveyCron()
    {
        try {
            $check_year = HolidayTutor::get()->count();
            if (!empty($check_year)) throw new \Exception('Exist the tutors of the last year.  You have to remove them first');

            $tutors = Tutor::where('tutor_status', 1)->whereRaw('created_at <= DATE_SUB(NOW(), INTERVAL 40 DAY)')->get();
            foreach ($tutors as $tutor) {
                $url_arr = [
                    'tutor_id' => $tutor->id,
                    'user_id' => $tutor->user->id,
                    'tutor_email' => $tutor->tutor_email,
                    'tutor_name' => $tutor->tutor_name,
                    'tutor_first_name' => $tutor->first_name,
                    'year' => date('Y')
                ];
                $url = base64_encode(serialize($url_arr));
                $short_url = $this->generateUniqueUrl($url);
                $holiday_tutor = HolidayTutor::create([
                    'tutor_id' => $tutor->id,
                    'status' => 1,
                    'year' => date('Y'),
                    'url' => $short_url,
                    'date_created' => date('d/m/Y H:i'),
                    'date_last_modified' => date('d/m/Y H:i')
                ]);

                $this->addHolidayTutorHistory([
                    'holiday_id' => $holiday_tutor->id,
                    'author' => auth()->user()->admin->admin_name,
                    'comment' => 'Sent end of holiday SMS to ' . $tutor->tutor_name . ' for ' . date('Y')
                ]);

                $price_tutors = $tutor->price_tutors;
                if (!empty($price_tutors)) {
                    foreach ($price_tutors as $price_tutor) {
                        $child = $price_tutor->chlid;
                        $parent = $price_tutor->parent;
                        if (empty($child)) continue;

                        $holiday_student = HolidayStudent::create([
                            'child_id' => $child->id,
                            'status' => 1,
                            'year' => date('Y'),
                            'last_tutor' => $tutor->id,
                            'date_created' => date('d/m/Y H:i'),
                            'date_last_modified' => date('d/m/Y H:i')
                        ]);
                        $this->addHolidayStudentHistory([
                            'holiday_id' => $holiday_student->id,
                            'author' => auth()->user()->admin->admin_name,
                            'comment' => 'Added ' . $child->child_name . ' to end of holiday student list for ' . date('Y')
                        ]);
                    }

                    $smsParams = [
                        'name' => $tutor->tutor_name,
                        'phone' => $tutor->tutor_phone,
                    ];
                    $params = [
                        'tutorfirstname' => $tutor->first_name,
                        'onlineURL' => 'https://alchemy.team/newyear?url=' .$short_url
                    ];
                    $this->sendSms($smsParams, 'new-year-holiday-tutor-sms', $params);

                    if ($tutor->accept_job_status == 1) {
                        $tutor->update([
                            'accept_job_status' => 0
                        ]);
                    } else {
                        HolidayTemp::create([
                            'tutor_id' => $tutor->id
                        ]);
                    }
                }
            }

            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "It is finished successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * send follow up sms to all awaiting holiday tutors.
     */
    public function tutorSurveySmsFollowup($final = false) {
        try {
            $holiday_tutors = HolidayTutor::where('status', 1)->where('year', date('Y'))->get();
            if (!empty($holiday_tutors)) {
                foreach($holiday_tutors as $holiday_tutor) {
                    $tutor = $holiday_tutor->tutor;
                    $smsParams = [
                        'name' => $tutor->tutor_name,
                        'phone' => $tutor->tutor_phone
                    ];
                    $params = [
                        'onlineURL' => 'https://alchemy.team/newyear?url=' . $holiday_tutor->url,
                        'tutorfirstname' => $tutor->first_name,
                        'date' => date('Y')
                    ];
                    if (!$final) $this->sendSms($smsParams, 'tutor-survey-follow-up-sms', $params);
                    else $this->sendSms($smsParams, 'tutor-survey-follow-up-final-sms', $params);

                    $comment = 'Sent end of holiday SMS reminder to ' . $tutor->tutor_name . ' for ' . date('Y');
                    if ($final) $comment = 'Sent end of holiday final SMS reminder to ' . $tutor->tutor_name . ' for ' . date('Y');
                    $this->addHolidayTutorHistory([
                        'holiday_id' => $holiday_tutor->id,
                        'author' => auth()->user()->admin->admin_name,
                        'comment' => $comment
                    ]);
                }
            }
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "It is finished successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * send follow up email to all awaiting holiday tutors.
     */
    public function tutorSurveyEmailFollowup() {
        try {
            $holiday_tutors = HolidayTutor::where('status', 1)->where('year', date('Y'))->get();
            if (!empty($holiday_tutors)) {
                foreach($holiday_tutors as $holiday_tutor) {
                    $tutor = $holiday_tutor->tutor;
                    $params = [
                        'email' => $tutor->tutor_email,
                        'link' => 'https://alchemy.team/newyear?url=' . $holiday_tutor->url,
                        'tutorfirstname' => $tutor->first_name,
                        'currentyear' => date('Y')
                    ];
                    $this->sendEmail($tutor->tutor_email, 'end-of-holiday-tutor-email', $params);

                    $comment = 'Sent end of holiday Email followup to ' . $tutor->tutor_name . ' for ' . date('Y');
                    $this->addHolidayTutorHistory([
                        'holiday_id' => $holiday_tutor->id,
                        'author' => auth()->user()->admin->admin_name,
                        'comment' => $comment
                    ]);
                }
            }
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "It is finished successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * deactivate all tutors from holiday tutors and make them to 'not continuing' in holiday tutors and add them to replacement-tutors, students to holiday-replacement, holiday-student.
     */
    public function tutorDeactivateCron() {
        try {
            $holiday_tutors = HolidayTutor::where('status', 1)->where('year', date('Y'))->get();
            if (!empty($holiday_tutors)) {
                foreach($holiday_tutors as $holiday_tutor) {
                    $tutor = $holiday_tutor->tutor;
                    $tutor->update(['tutor_status' => 0]);
                    $this->addTutorHistory([
                        'tutor_id' => $tutor->id,
                        'author' => auth()->user()->admin->admin_name,
                        'comment' => "Sent to inactive.  Reason: Don't respond to end of holiday " . date('Y') . " message"
                    ]);
                    
                    $smsParams = [
                        'name' => $tutor->tutor_name,
                        'phone' => $tutor->tutor_phone
                    ];
                    $params = [];
                    $this->sendSms($smsParams, 'tutor-deactivate-cron-sms', $params);

                    if ($tutor->state == 'QLD') {
                        $params = [
                            'tutorname' => $tutor->tutor_name,
                            'tutoremail' => $tutor->tutor_email
                        ];
                        $this->sendEmail($tutor->tutor_email, 'inactive-qld-tutor-email', $params);
                    }

                    $holiday_tutor->update(['status' => 3]);

                    $price_tutors = $tutor->price_tutors;
                    if (!empty($price_tutors)) {
                        foreach ($price_tutors as $price_tutor) {
                            $child = $price_tutor->child;
                            $parent = $price_tutor->parent;
                            if (empty($child) || $child->child_status == 1) continue;

                            $replacement_tutors_count = ReplacementTutor::where('tutor_id', $tutor->id)->where('child_id', $child->id)->count();
                            if (empty($replacement_tutors_count)) continue;
                            
                            $last_session = Session::where('tutor_id', $tutor->id)->where('child_id', $child->id)->orderBy('id', 'desc')->first();

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
                        }
                    }
                }
            }
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "It is finished successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * send follow up email to all awaiting holiday tutors.
     */
    public function emptyHolidayData() {
        try {
            HolidayReplacement::truncate();
            HolidayReplacementHistory::truncate();
            HolidayStudent::truncate();
            HolidayStudentHistory::truncate();
            HolidayTutor::truncate();
            HolidayTutorHistory::truncate();
            HolidayTemp::truncate();
            
            $this->dispatch('showToastrMessage', [
                'status' => 'success',
                'message' => "All data was deleted successfully!"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
}
