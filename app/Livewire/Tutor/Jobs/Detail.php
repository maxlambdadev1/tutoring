<?php

namespace App\Livewire\Tutor\Jobs;

use App\Models\Job;
use App\Models\Session;
use App\Models\ReplacementTutor;
use Carbon\Carbon;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\PriceCalculatable;
use App\Trait\Sessionable;
use App\Trait\WithLeads;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class Detail extends Component
{
    use Functions, WithLeads, PriceCalculatable, Mailable, Sessionable;


    public $job;
    public $tutor;
    public $under_18 = false;
    public $online_limit;
    public $experienced_limit;
    public $job_availability;
    public $special_request_response;

    public function mount($job_id)
    {
        $this->init($job_id);
    }

    private function init($job_id)
    {
        $job = Job::find($job_id);
        $this->online_limit = $this->getOption('online-limit');
        $this->experienced_limit = $this->getOption('experience-limit') ?? 50;
        $tutor = auth()->user()->tutor;

        $today = new \DateTime('now');
        $birth = \DateTime::createFromFormat('d/m/Y', trim($tutor->birthday));
        $interval = $today->diff($birth);
        if ($interval->y < 18) $this->under_18 = true;

        if (!empty($job)) {
            $job_offer = $job->job_offer;
            if (!empty($job_offer)) {
                $datetime = new \DateTime('Australia/Sydney');
                $job->job_type  = 'hot';
                if ($job_offer->expiry == 'permanent') {
                    if ($job_offer->offer_type == 'fixed') $job->job_offer_price = $this->getCoreTutorPrice($job->session_type_id) + $job_offer->offer_amount;
                } elseif ($datetime->getTimestamp() <= $job_offer->expiry) {
                    if ($job_offer->offer_type == 'fixed')  $job->job_offer_price = $this->getCoreTutorPrice($job->session_type_id) + $job_offer->offer_amount;
                }
            }

            $hour_diff = $this->getTimezoneDiffHours($job->parent_id, $tutor->id);
            $av_str = $job->date ?? '';
            if (!empty($hour_diff)) $av_str = $this->convertTimezone($av_str, $hour_diff);
            $av_exp = explode(',', $av_str) ?? [];
            $formatted_date = [];
            foreach ($av_exp as $av) { //ma7
                $item = [];
                $ses_date = $this->generateSessionDate($av, $job->start_date, false);
                $date = $this->getAvailabilitiesFromString1($av)[0];
                $item = [
                    'full_date' => explode(' ', $date)[0] . ' ' . $ses_date['date'] . ' at ' . $ses_date['time'],
                    'date' => $date
                ];
                $formatted_date[$av] = $item;
            }
            $job->formatted_date = $formatted_date;

            $job->coords = [
                'tutor_lat' => $tutor->lat ?? 0,
                'tutor_lon' => $tutor->lon ?? 0,
                'child_lat' => $job->parent->parent_lat ?? 0,
                'child_lon' => $job->parent->parent_lon ?? 0
            ];
        }

        $this->job = $job;
        $this->tutor = $tutor;
    }

    public function acceptJob()
    {
        try {
            $job = $this->job;
            $this->init($job->id);

            $tutor = $this->tutor;

            if ($job->job_status !== 0) throw new \Exception("The job does not exist.");
            if ($tutor->accept_job_status < 1) throw new \Exception("You are blocked from accepting the job. Please contact to supporter.");
            if (!!$this->under_18 && $job->session_type_id == 1) throw new \Exception("You can't accept all f2f jobs.");
            if (!empty($job->prefered_gender) && $tutor->gender != $job->prefered_gender) throw new \Exception("This job has a prefered gender.");
            if (!!$job->vaccinated && !$tutor->vaccinated && $job->session_type_id == 1) throw new \Exception("This job is for only experienced tutors.");

            $accepted_jobs = Job::where('accepted_by', $tutor->id)
                ->whereRaw("STR_TO_DATE(accepted_on, '%d/%m/%Y')=CURDATE()")->count();
            if ($accepted_jobs >= 3) throw new \Exception("You have accepted the maximum student opportunities for one day - please try again tomorrow!");

            $parent = $job->parent;
            $child = $job->child;
            if (!empty($job->special_request_content)) {
                if (empty($this->special_request_response)) throw new \Exception("You have to write your response to parent's request.");

                $job->update([
                    'job_status' => 4,
                    'accepted_by' => $tutor->id,
                    'last_updated' => date('d/m/Y H:i'),
                    'converted_by' => 'tutor',
                    'special_request_response' => $this->special_request_response,
                    'tutor_suggested_session_date' => $this->job_availability
                ]);
                $this->addTutorHistory([
                    'tutor_id' => $tutor->id,
                    'author' => $tutor->tutor_name,
                    'comment' => "Tutor accepted a new student(special request job): " . $child->child_name
                ]);

                $params = [
                    'tutorfirstname' => $tutor->first_name ?? '',
                    'subject' => $job->subject,
                    'grade' => $child->child_year,
                    'email' => $tutor->tutor_email,
                    'sessiontype' => $job->session_type_id == 1 ? 'Face to Face' : 'Online',
                    'specialrequirement' => $job->special_request_content
                ];
                $this->sendEmail($tutor->tutor_email, 'special-requirement-tutor-submission-email', $params);

                //send email to supporter
            } else {
                $job->update([
                    'job_status' => 1,
                    'accepted_by' => $tutor->id,
                    'last_updated' => date('d/m/Y H:i')
                ]);
                $this->addJobHistory([
                    'job_id' => $job->id,
                    'author' => $tutor->tutor_name,
                    'comment' => "Tutor accepted a new student: " . $child->child_name
                ]);

                $tutor->update([
                    'break_count' => 0,
                    'seeking_students' => 0,
                    'seeking_students_timestamp' => time()
                ]);

                $datetime = new \DateTime(env('TIMEZONE'));
                $job_offer = $job->job_offer;
                if (!empty($job_offer)) {
                    if ($job_offer->expiry == 'permanent' || $job_offer->expiry >= $datetime->getTimestamp()) {
                        $this->addTutorPriceOffer($tutor->id, $parent->id, $child->id, $job_offer->offer_amount, $job_offer->offer_type);
                    }
                }

                if ($job->job_type == 'creative') {
                    $session_price = 100;
                    if ($job->session_type_id == 1) $tutor_price = 65;
                    else  $tutor_price = 50;
                } else {
                    $session_price = $this->calcSessionPrice($parent->id, $job->session_type_id);
                    $tutor_price = $this->calcTutorPrice($tutor->id, $parent->id, $child->id, $job->session_type_id);
                }

                $this->checkTutorFirstSession($tutor->id);

                $ses_date = $this->generateSessionDate($this->job_availability, $job->start_date);
                $session_date = $ses_date['date'] ?? '';
                $session_time = $ses_date['time'] ?? '';

                $session = Session::create([
                    'type_id' => $job->session_type_id,
                    'session_status' => 3,
                    'tutor_id' => $tutor->id,
                    'parent_id' => $parent->id,
                    'child_id' => $child->id,
                    'session_date' => $session_date,
                    'session_time' => $session_time,
                    'session_subject' => $job->subject,
                    'session_is_first' => 1,
                    'session_price' => $session_price,
                    'session_tutor_price' => $tutor_price,
                    'session_last_changed' => date('d/m/Y H:i')
                ]);

                $parent_time = Carbon::createFromFormat('G:i', $session_time)->format('g:i A');
                $tutor_time = $parent_time;
                $hour_diff = $this->getTimezoneDiffHours($parent->id, $tutor->id);
                if (!empty($hour_diff)) $tutor_time = $this->calculateTime($parent_time, $hour_diff);
                $params = [
                    'firstname' => $tutor->first_name,
                    'studentname' => $child->child_name,
                    'studentfirstname' => $child->first_name,
                    'grade' => $child->child_year,
                    'date' => $session_date,
                    'time' => $tutor_time,
                    'subject' => $job->subject,
                    'notes' => $job->job_notes,
                    'mainresult' => $job->main_result ?? '-',
                    'performance' => $job->performance ?? '-',
                    'attitude' => $job->attitude ?? '-',
                    'mind' => $job->mind ?? '-',
                    'personality' => $job->personality ?? '-',
                    'favourite' => $job->favourite ?? '-',
                    'address' => $parent->parent_address . ', ' . $parent->parent_suburb . ', ' . $parent->parent_postcode,
                    'parentname' => $parent->parent_name ?? '',
                    'parentphone' => $parent->parent_phone,
                    'email' => $tutor->tutor_email,
                    'tutoremail' => $tutor->tutor_email,
                    'onlineURL' => $tutor->online_url,
                ];

                if ($job->job_type == 'replacement' && !empty($job->replacement_id)) {
                    $replacement = ReplacementTutor::find($job->replacement_id);
                    if (!empty($replacement)) {
                        $params['tutornotes'] = $replacement->tutor_notes;
                        $replacement->update([
                            'replacement_tutor_id' => $tutor->id,
                        ]);
                        if ($job->session_type_id == 2) $this->sendEmail($tutor->tutor_email, 'tutor-first-online-session-details-email', $params);
                        else  $this->sendEmail($tutor->tutor_email, 'tutor-first-session-details-replacement-email', $params);
                    }
                } else {
                    if ($job->job_type == 'creative') {
                        if ($job->session_type_id == 2) $this->sendEmail($tutor->tutor_email, 'tutor-creative-online-session-details-email', $params);
                        else  $this->sendEmail($tutor->tutor_email, 'tutor-creative-session-details-email', $params);
                    } else {
                        if ($job->session_type_id == 2) $this->sendEmail($tutor->tutor_email, 'tutor-first-online-session-details-email', $params);
                        else  $this->sendEmail($tutor->tutor_email, 'tutor-first-session-details-email', $params);
                    }
                }

                $params['tutorname'] = $tutor->tutor_name;
                $params['tutorfirstname'] = $tutor->first_name;
                if ($job->job_type == 'creative') {
                    $sms_body = "Hi " . $params['tutorfirstname'] . "! Your creative writing workshop with " . $params['studentname'] . "  has been confirmed for " . $tutor_time . " on " . $params['date'] . ". You will receive an email with details shortly! Team Alchemy";
                } else {
                    $sms_body = "Hozzah! Your first session with " . $params['studentname'] . " is confirmed for " . $params['date'] . " at " . $tutor_time . ". Please check your email for details and don’t hesitate to get in touch with any questions!";
                }
                $smsParams = [
                    'phone' => $tutor->tutor_phone,
                    'name' => $tutor->tutor_name,
                ];
                $this->sendSms($smsParams, $sms_body);

                if ($job->job_type == 'creative') {
                    $sms_body = "Hi " . $parent->parentfirstname . "! Great news - we’ve lined up a creative writing workshop for " . $params['studentfirstname'] . "  on " . $params['date']  . " at " . $parent_time . ". You will receive an email with details shortly! Team Alchemy";
                } else {
                    $sms_body = "Hi " . $params['parentfirstname'] . ", great news! Your first session with an Alchemy Tutor has been confirmed for " . $params['date'] . " at " . $parent_time . ". Please check your email for details!";
                }
                $smsParams = [
                    'phone' => $params['parentphone'],
                    'name' => $parent->parent_name,
                ];
                $this->sendSms($smsParams, $sms_body);

                $params['time'] = $parent_time;
                $params['parentfirstname'] = $parent->parent_first_name;
                $params['tutorphone'] = $tutor->tutor_phone;
                $params['price'] = $session_price;
                $params['email'] = $parent->parent_email;
                $params['onlineURL'] = $tutor->online_url;
                $params['tutorlink'] = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://') . env('TUTOR') . '/tutor/' . $tutor->id;

                if ($job->job_type == 'creative') {
                    if ($job->session_type_id == 2) $this->sendEmail($parent->parent_email, 'parent-creative-online-session-details-email', $params);
                    else  $this->sendEmail($parent->parent_email, 'parent-creative-session-details-email', $params);
                } else {
                    if ($job->session_type_id == 2) {
                        if (!empty($job->thirdparty_org))  $this->sendEmail($parent->parent_email, 'third-party-first-lesson-details-online', $params);
                        else  $this->sendEmail($parent->parent_email, 'parent-first-online-session-detail-email', $params);
                    } else {
                        if (!empty($job->thirdparty_org))  $this->sendEmail($parent->parent_email, 'third-party-first-lesson-details-f2f', $params);
                        else  $this->sendEmail($parent->parent_email, 'parent-first-session-detail-email', $params);
                    }
                }
                if (!empty($job->thirdparty_org)) {
                    $params['email'] = $job->thirdparty_org->primary_contact_email;
                    $params['thirdpartyorgname'] = $job->thirdparty_org->organisation_name;
                    $params['thirdpartyorgcontactfirstname'] = $job->thirdparty_org->primary_contact_first_name;
                    $this->sendEmail($params['email'], 'third-party-organisation-first-lesson-details', $params);
                }

                $this->deleteJobReschedule($job->id, $tutor->id);

                $job->update([
                    'session_id' => $session->id,
                    'last_updated' => date('d/m/Y H:i'),
                ]);

                $this->addConversionTarget($job->id, $session->id);

                if ($job->job_type !== 'creative') {
                    $this->addFirstSessionTarget($session->id);
                } else {
                    $send_to = 'alecks.annear@alchemytuition.com.au';
                    $params = [
                        'parentname' => $parent->parent_name,
                        'studentname' => $child->child_name,
                        'studentbirthday' => $child->child_birthday,
                        'vouchernumber' => $job->voucher_number
                    ];
                    $this->sendEmail($send_to, 'new-creative-kids-creation', $params);
                }
            }

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function render()
    {
        return view('livewire.tutor.jobs.detail');
    }
}
