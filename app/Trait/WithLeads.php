<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\Tutor;
use App\Models\ReplacementTutor;
use App\Models\AlchemyParent;
use App\Models\BookingTarget;
use App\Models\Child;
use App\Models\Session;
use App\Models\User;
use App\Models\RejectedJob;
use App\Models\Job;
use App\Models\TutorOfferVolume;
use App\Models\JobReschedule;
use App\Models\JobIgnore;
use App\Models\Availability;
use App\Models\JobOffer;
use App\Models\JobVolumeCount;
use App\Models\PriceParentDiscount;
use App\Models\SessionType;
use App\Models\ThirdpartyOrganisation;
use App\Models\WaitingLeadOffer;
use App\Trait\Functions;
use App\Trait\Mailable;
use App\Trait\PriceCalculatable;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;
use PhpParser\Node\Stmt\TryCatch;

trait WithLeads
{
    use Functions, Mailable, PriceCalculatable;

    public const LEAD_SOURCE = [
        'Phone',
        'Chat',
        'Email',
        'Replacement',
        'Online',
    ];
    public const VALID_UNTIL = [
        '1 hour',
        '2 hours',
        '5 hours',
        '12 hours',
        '1 day',
        '1 week',
        '1 month',
        'permanent',
    ];
    public const PROGRESS_STATUS = [
        'New',
        'Parent call - booked',
        'Parent call - chasing',
        'Student lead',
        'Looking for tutor',
        'Timecheck sent',
        'Parent update sent',
        'Parent options sent',
    ];

    /**
     * return @Tutors
     */
    public function searchTutors($str, $length)
    {
        $tutors = [];
        $query = Tutor::where('tutor_name', 'like', '%' . $str . '%')->orwhere('tutor_email', 'like', '%' . $str . '%')->orwhere('tutor_phone', 'like', '%' . $str . '%')->orderBy('tutor_name');
        if (empty($length)) {
            $tutors = $query->get();
        } else {
            $tutors = $query->limit($length)->get();
        }
        return $tutors;
    }

    /**
     * @param $str, $length
     * @return array 
     */
    public function searchChildren($str, $length = '')
    {
        $children = [];
        $query = Child::where('child_name', 'like', '%' . $str . '%')->orderBy('child_name');
        if (empty($length)) {
            $children = $query->get();
        } else {
            $children = $query->limit($length)->get();
        }
        return $children;
    }

    /**
     * @param $str, $length
     * @return array 
     */
    public function searchParents($str, $length = '')
    {
        $parents = [];
        $query = AlchemyParent::where('parent_first_name', 'like', '%' . $str . '%')->orWhere('parent_last_name', 'like', '%' . $str . '%')->orWhere('parent_email', 'like', '%' . $str . '%')->orwhere('parent_phone', 'like', '%' . $str . '%')->orderBy('parent_first_name');
        if (empty($length)) {
            $parents = $query->get();
        } else {
            $parents = $query->limit($length)->get();
        }
        return $parents;
    }

    public function searchParentsChildren($str, $length = '')
    {
        try {
            $people = [];
            $query = AlchemyParent::join('alchemy_children', 'alchemy_parent.id', '=', 'alchemy_children.parent_id')
                ->where('parent_first_name', 'like', '%' . $str . '%')
                ->orWhere('parent_last_name', 'like', '%' . $str . '%')
                ->orWhere('parent_email', 'like', '%' . $str . '%')
                ->orWhere('child_name', 'like', '%' . $str . '%')
                ->orWhere('child_first_name', 'like', '%' . $str . '%')
                ->orWhere('child_last_name', 'like', '%' . $str . '%')
                ->orderBy('parent_id');

            if (empty($length)) {
                $people = $query->get();
            } else {
                $people = $query->limit($length)->get();
            }
            return $people;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $str : string, $length : int
     * @return array (Session)
     */
    public function searchStudentsFromSession($str, $length = '')
    {
        $query = Session::query()
            ->WhereHas('child', function ($query) use ($str) {
                $query->where('child_name', 'like', '%' . $str . '%');
            })
            ->groupBy('child_id', 'parent_id', 'tutor_id')
            ->orderBy('child_id');

        if (empty($length)) {
            $sessions = $query->get();
        } else {
            $sessions = $query->limit($length)->get();
        }
        return $sessions;
    }

    public function CreateJobFromData($inputData)
    {
        try {
            $thirdparty_org_id =  null;
            if (isset($inputData['thirdparty_org_id']) && !empty($inputData['thirdparty_org_id'])) {
                if (!empty(ThirdpartyOrganisation::find($inputData['thirdparty_org_id']))) $thirdparty_org_id = $inputData['thirdparty_org_id'];
            }
            $is_from_main = $inputData['is_from_main'] ? 1 : 0;
            $experienced_tutor = 0;
            if (isset($inputData['experienced_tutor']) && !!$inputData['experienced_tutor']) $experienced_tutor = 1;
            $vaccinated = 0;
            if (isset($inputData['vaccinated']) && !!$inputData['vaccinated']) $vaccinated = 1;
            $hide_lead = 0;
            if (isset($inputData['hide_lead']) && !!$inputData['hide_lead']) $hide_lead = 1;
            $automate = 0;
            if (isset($inputData['automate']) && !!$inputData['automate'] && $hide_lead == 0) $automate = 1;
            $google_ads = 0;
            $google_booking = 'NO';
            if (isset($inputData['google_ads']) && !!$inputData['google_ads']) {
                $google_ads = 1;
                $google_booking = 'YES';
            }
            $session_type_id = 1;
            if (isset($inputData['session_type_id']) && !empty($inputData['session_type_id'])) $session_type_id = $inputData['session_type_id'];
            $session_type = SessionType::find($session_type_id)->first()->name;
            $progress_status = 'New';

            if (User::where('email', $inputData['parent_email'])->where('role', '!=', 3)->count() > 0) {
                throw new \Exception('The email is already used');
            }

            $user = User::updateOrCreate(
                ['email' => $inputData['parent_email']],
                [
                    'role' => 3,
                    'password' => bcrypt('password'),
                ]
            );
            $coords = $this->getCoord(urlencode($inputData['address']));
            if (empty($coords['lat'])) $coords['lat'] = 0;
            if (empty($coords['lon'])) $coords['lon'] = 0;
            $location = $coords['suburb'];
            $address_mailchimp = $coords['address'];

            $parent_existed = AlchemyParent::where('parent_email', $inputData['parent_email'])->count() > 0 ? true : false;

            if (!$parent_existed) {
                $parent = AlchemyParent::create(
                    [
                        'parent_email' => $inputData['parent_email'],
                        'parent_first_name' => ucwords($inputData['parent_first_name']),
                        'parent_last_name' => ucwords($inputData['parent_last_name']),
                        'parent_phone' => $inputData['parent_phone'],
                        'parent_state' => $inputData['state'],
                        'parent_postcode' => $inputData['postcode'],
                        'parent_address' => $coords['address'],
                        'parent_suburb' => $coords['suburb'],
                        'parent_lat' => $coords['lat'],
                        'parent_lon' => $coords['lon'],
                        'user_id' => $user->id,
                        'subscribe' => 1,
                        'thirdparty_org_id' => $thirdparty_org_id,
                    ]
                );
            } else {
                $parent = AlchemyParent::updateOrCreate(
                    ['parent_email' => $inputData['parent_email']],
                    [
                        'parent_first_name' => ucwords($inputData['parent_first_name']),
                        'parent_last_name' => ucwords($inputData['parent_last_name']),
                        'parent_phone' => $inputData['parent_phone'],
                        'parent_state' => $inputData['state'],
                        'parent_postcode' => $inputData['postcode'],
                        'user_id' => $user->id,
                        'subscribe' => 1,
                        'thirdparty_org_id' => $thirdparty_org_id,
                    ]
                );

                if (!empty($coords['lat']) && !empty($coords['lon'])) {
                    $parent->update([
                        'parent_address' => $coords['address'],
                        'parent_suburb' => $coords['suburb'],
                        'parent_lat' => $coords['lat'],
                        'parent_lon' => $coords['lon'],
                    ]);
                }
            }

            $parent->referral_code = strval($parent->id + 10000);
            $parent->save();

            //check referral code for new user
            $referral_code = "";
            if (!$parent_existed && isset($inputData['referral']) && !empty($inputData['referral'])) {
                $referral_parent = AlchemyParent::where('referral_code', $inputData['referral'])->first();
                if (!empty($referral_parent) && ($parent->id !== $referral_parent->id)) {
                    if (!$parent->referralParent) {
                        $parent->referred_id = $referral_parent->id;
                        $parent->save();
                        //send email to referral parent
                        $params = array();
                        $params['email'] = $referral_parent->parent_email;
                        $params['parentfirstname'] = $referral_parent->parent_first_name;
                        $params['referralcode'] = $referral_parent->referral_code;
                        $this->sendEmail($referral_parent->parent_email, "parent-referral-notification", $params);
                        $referral_code = $referral_parent->referral_code;
                    }
                }
            }

            $student_number = 0;
            foreach ($inputData['students'] as $student) {
                if (empty($student['student_first_name'])) continue;
                else $student_number++;

                $date_for_body = implode(',', $this->arrayFlatten($student['date']));
                $student_first_name = ucwords($student['student_first_name']);
                $student_last_name = ucwords($student['student_last_name']);

                $no_availability = false;
                if (!isset($student['date']) || empty($student['date'])) {
                    $hide_lead = 1;
                    $student_date = '';
                    $no_availability = true;
                } else {
                    $student_date = $this->generateBookingAvailability($student['date']); //ma7,sp530...
                }

                $graduation_year = $this->getGraduationYear($student['grade']);
                $child = Child::updateOrCreate(
                    [
                        'parent_id' => $parent->id,
                        'child_name' => $student['student_first_name'] . " " . $student['student_last_name'],
                    ],
                    [
                        'child_first_name' => $student['student_first_name'],
                        'child_last_name' => $student['student_last_name'],
                        'child_year' => $student['grade'],
                        'child_school' => $student['student_school'],
                        'graduation_year' => $graduation_year,
                        'google_ads' => $google_ads
                    ]
                );

                $job = Job::Create([
                    'job_type' => 'regular',
                    'progress_status' => $progress_status,
                    'parent_id' => $parent->id,
                    'child_id' => $child->id,
                    'date' => $student_date,
                    'start_date' => $student['start_date'],
                    'subject' => $student['subject'],
                    'location' => $location,
                    'prefered_gender' => $student['prefered_gender'],
                    'job_notes' => $student['notes'],
                    'main_result' => $student['main_result'],
                    'performance' => $student['student_performance'],
                    'attitude' => $student['student_attitude'],
                    'mind' => $student['student_mind'],
                    'personality' => $student['student_personality'],
                    'favourite' => $student['student_favourite'],
                    'job_status' => 0,
                    'session_type_id' => $session_type_id,
                    'hidden' => $hide_lead,
                    'source' => !empty($inputData['lead_source']) ? $inputData['lead_source'] : 'booking',
                    'create_time' => date('d/m/Y H:i'),
                    'last_updated' => date('d/m/Y H:i'),
                    'vaccinated' => $vaccinated,
                    'experienced_tutor' => $experienced_tutor,
                    'automation' => $automate,
                    'special_request_content' => isset($inputData['special_request_content']) ? $inputData['special_request_content'] : null,
                    'is_from_main' => $is_from_main,
                    'thirdparty_org_id' => $thirdparty_org_id,
                ]);

                $param = array();
                $param['firstname'] = $parent->parent_first_name;
                $param['studentname'] = $child->child_first_name;
                $param['email'] = $parent->parent_email;
                if (isset($inputData['welcome_email']) && !empty($inputData['welcome_email'])) {
                    $this->sendEmail($parent->parent_email, 'parent-booking-email', $param);
                } elseif (empty($inputData['lead_source'])) {
                    $this->sendEmail($parent->parent_email, 'parent-booking-email', $param);
                }

                if (!empty($student['team_notes'])) {
                    $this->addJobHistory([
                        'author' => '',
                        'comment' => $student['team_notes'],
                        'job_id' => $job->id
                    ]);
                    $this->addStudentHistory([
                        'author' => '',
                        'comment' => $student['team_notes'],
                        'child_id' => $child->id
                    ]);
                }

                if (!empty($inputData['ignore_tutors'])) {
                    $ignore_comment = 'Ignored tutors: ';
                    $this->ignoreTutorsForJob($job->id, $inputData['ignore_tutors']);
                    foreach ($inputData['ignore_tutors'] as $tutor_id) {
                        $tutor = Tutor::find($tutor_id);
                        $ignore_comment .= $tutor->tutor_name . ' (' . $tutor->tutor_email . '), ';
                    }
                    $this->addJobHistory([
                        'job_id' => $job->id,
                        'comment' => $ignore_comment
                    ]);
                }
                //send email to admin
                $params = [
                    'parentname' => ucwords($parent->parent_name),
                    'parentphone' => $parent->parent_phone,
                    'parentemail' => $parent->parent_email,
                    'referralcode' => $referral_code,
                    'state' => $inputData['state'],
                    'address' => $inputData['address'],
                    'postcode' => $inputData['postcode'],
                    'sessiontype' => $session_type,
                    'googlebooking' => $google_booking,
                    'studentname' => $child->child_name,
                    'school' => $child->child_school,
                    'grade' => $child->child_year,
                    'subject' => $job->subject,
                    'date' => $date_for_body,
                    'notes' => $job->job_notes,
                    'startdate' => $job->start_date
                ];
                $this->sendEmail('nadine.cook@alchemytuition.com.au', 'create-job-to-admin-email', $params);
                if ($google_ads == 1) {
                    $this->sendEmail('matt.ahern@alchemytuition.com.au', 'create-job-to-admin-email', $params);
                }
            }

            if ($automate == 1 && $hide_lead == 0 && $session_type_id == '1' && !$no_availability) {
                // $this->functions->find_tutor_for_job_av($job_id);
            }
            if ($automate == 1 && $hide_lead == 0 && $session_type_id == '2' && !$no_availability) {
                // $this->functions->find_tutor_for_online($job_id);
            }

            $datetime = new \DateTime('Australia/Sydney');
            BookingTarget::create([
                'job_id' => $job->id,
                'source' => !empty($inputData['lead_source']) ? $inputData['lead_source'] : 'booking',
                'booking_date' => $datetime->format('d/m/Y')
            ]);

            //add job offer
            $option = $this->getOption('hot-job-offer');
            if (!empty($option)) {
                $hot_lead = unserialize($option);
                $today = new \DateTime();
                if ($hot_lead['age'] > $today->getTimestamp() && (empty($hot_lead['lead_type']) || $session_type_id == $hot_lead['lead_type'])) {
                    $this->saveJobOffer($job->id, 'fixed', $hot_lead['offer'], 'permanent');
                }
            }
            if (!empty($inputData['tutor_apply_offer'])) {
                $this->saveJobOffer($job->id, $inputData['offer_type'], $inputData['offer_amount'], $inputData['offer_valid']);
            }

            //add parent discount
            if (!empty($inputData['parent_apply_discount'])) {
                $this->saveParentDiscount($parent->id, $inputData['discount_type'], $inputData['discount_amount']);
            }

            //add/update mailchimp user

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function ignoreTutorsForJob($job_id, $tutor_id_array)
    {
        foreach ($tutor_id_array as $tutor_id) {
            JobIgnore::create([
                'job_id' => $job_id,
                'tutor_id' => $tutor_id
            ]);
        }
    }

    /**
     * get the array of tutor's id in JobIgnore from each job
     * @param $job_id : Job id
     * @return array : Tutor id array
     */
    public function getIgnoredTutorsForJob($job_id)
    {
        return JobIgnore::where('job_id', $job_id)->pluck('tutor_id')->toArray();
    }

    /**
     * @param $post = ['assigned_tutor' => 2601, 'availability' => 'mon-7:00 AM', 'custom_date' => 30/05/2024 6:28 AM]
     */
    public function assignLead($job_id, $post)
    {
        try {
            $job = Job::find($job_id);
            if ($job->job_status == 1) throw new \Exception('This lead was already accepted by someone');
            if (empty($post['assigned_tutor'])) throw new \Exception('Please select a tutor first.');

            $dt_now = new \DateTime('now');
            $start_date = $dt_now->format('d/m/Y');
            $check_start_date = explode('/', $job->start_date);
            if (!empty($check_start_date[1])) $start_date = $job->start_date; //dd/mm/yyyy

            if (!empty($post['availability'])) { //mon-7:00 AM
                $av_arr = explode('-', $post['availability']); //['mon', '7:00 PM']
                $session_date = $this->getNextDateByDay($start_date, $this::WEEK_DAYS[$av_arr[0]])->format('d/m/Y'); //27/05/2024
                $session_time = Carbon::createFromFormat('g:i A', $av_arr[1])->format('G:i'); //19:00
            } else if (!empty($post['custom_date'])) {
                $custom_date = (new \DateTime('now'))->createFromFormat('d/m/Y h:i A', $post['custom_date']);
                $session_date = $custom_date->format('d/m/Y');
                $session_time = $custom_date->format('H:i');
            } else throw new \Exception('select fields correctly');

            $tutor = Tutor::find($post['assigned_tutor']);

            $this->createSessionFromJob($job_id, $tutor->id, $session_date, $session_time, 'admin');
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Accept job and create first session
     * @param mixed $job_id
     * @param $session_date : '23/05/2024', 
     * @param $session_time : '16:30' 
     * @return void
     */
    public function createSessionFromJob($job_id, $tutor_id, $session_date, $session_time, $converted_by = 'tutor')
    {
        try {
            $job = Job::find($job_id);
            $tutor = Tutor::find($tutor_id);
            $parent = $job->parent;
            $child = $job->child;

            $job->update([
                'job_status' => 1,
                'accepted_by' => $tutor->id,
                'last_updated' => date('d/m/Y H:i'),
                'accepted_on' => date('d/m/Y H:i'),
                'converted_by' => $converted_by,
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
            $sms_params = [
                'phone' => $tutor->tutor_phone,
                'name' => $tutor->tutor_name,
            ];
            $this->sendSms($sms_params, $sms_body);

            if ($job->job_type == 'creative') {
                $sms_body = "Hi " . $parent->parent_first_name . "! Great news - we’ve lined up a creative writing workshop for " . $params['studentfirstname'] . "  on " . $params['date']  . " at " . $parent_time . ". You will receive an email with details shortly! Team Alchemy";
            } else {
                $sms_body = "Hi " . $parent->parent_first_name . ", great news! Your first session with an Alchemy Tutor has been confirmed for " . $params['date'] . " at " . $parent_time . ". Please check your email for details!";
            }
            $sms_params = [
                'phone' => $params['parentphone'],
                'name' => $parent->parent_name,
            ];
            $this->sendSms($sms_params, $sms_body);

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
                'accepted_on' => date('d/m/Y H:i'),
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
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * toggle 'hidden' status of the job
     * @param $job_id
     */
    public function toggleShowHideLead($job_id)
    {
        try {
            $job = Job::find($job_id);
            if (empty($job->date)) throw new \Exception("Can't edit lead status since it does not have availabilities");

            $job->update([
                'hidden' => $job->hidden == 1 ? 0 : 1,
                'last_updated' => date('d/m/Y H:i'),
                'automation' => 0
            ]);

            $this->addJobHistory([
                'author' => auth()->user()->admin->admin_name,
                'comment' => 'Lead ' . ($job->hidden == 1 ? 'hidden' : 'unhidden'),
                'job_id' => $job->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * delete the reason
     * @param $job_id, $reason
     */
    public function deleteLead($job_id, $reason)
    {
        $job = Job::find($job_id);
        if (empty($job)) throw new \Exception("This lead was already deleted");
        if ($job->job_status == 1) throw new \Exception("This lead was already accepted by someone");
        if ($job->job_status == 3) {
            $waiting_job = WaitingLeadOffer::where('job_id', $job_id)->where('status', 0)->get();
            if (!empty($waiting_job)) throw new \Exception("This lead was already accepted by someone");
        }

        $this->addJobHistory([
            'author' => auth()->user()->admin->admin_name,
            'comment' => 'Deleted this job. reason:' . $reason,
            'job_id' => $job->id
        ]);

        $this->deleteJobReschedule($job_id);
        $job->update([
            'job_status' => 2,
            'reason' => $reason,
        ]);
    }

    public function deleteJobReschedule($job_id, $exception_tutor_id = null)
    {
        $job = Job::find($job_id);
        $child = $job->child;
        $notified_tutors = array();
        $reschedules = JobReschedule::where('job_id', $job->id)->get();
        if (!empty($reschedules)) {
            foreach ($reschedules as $reschedule) {
                $reschedule_tutor = Tutor::find($reschedule->tutor_id);
                if ($exception_tutor_id !== $reschedule_tutor->id) {
                    $params = [
                        'tutorfirstname' => $reschedule_tutor->tutor_name,
                        'grade' => $child->child_year,
                        'address' => $job->location,
                        'email' => $reschedule_tutor->user->email
                    ];
                    if (!in_array($reschedule_tutor->id, $notified_tutors)) {
                        $this->sendEmail($reschedule_tutor->user->email, 'tutor-accept-lead-alternate-date', $params);
                        array_push($notified_tutors, $reschedule_tutor->id);
                    }
                }
                $reschedule->delete();
            }
        }
    }

    public function saveJobOffer($job_id, $offer_type, $offer_amount, $offer_valid)
    {
        if (!empty($offer_type) && !empty($offer_amount)) {
            $prev_job_offer = JobOffer::where('job_id', $job_id)->first();
            $datetime = new \DateTime('Australia/Sydney');
            if ($offer_valid == 'permanent') $valid = $offer_valid;
            else if (!empty($prev_job_offer) && $offer_valid == $prev_job_offer->expiry) $valid = $offer_valid;
            else {
                $datetime->modify('+' . $offer_valid . '');
                $valid = $datetime->getTimestamp();
            }
            JobOffer::updateOrCreate(
                [
                    'job_id' => $job_id,
                ],
                [
                    'offer_amount' => $offer_amount,
                    'offer_type' => $offer_type,
                    'expiry' => $valid
                ]
            );

            $comment = 'Hot pricing added for ' . ($offer_type == 'fixed' ? '\$' : '\%') . $offer_amount;
            if (!empty($prev_job_offer) && ($offer_type !== $prev_job_offer->offer_type && $offer_amount !== $prev_job_offer->offer_amount)) {
                $comment = 'Updated hot pricing for ' . ($offer_type == 'fixed' ? '\$' : '\%') . $offer_amount;
            }
            $this->addJobHistory([
                'job_id' => $job_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment
            ]);
        } else {
            JobOffer::where('job_id', $job_id)->delete();
        }
    }

    public function saveParentDiscount($parent_id, $discount_type, $discount_amount)
    {
        if (!empty($discount_type) && !empty($discount_amount)) {
            $prev_offer = PriceParentDiscount::where('parent_id', $parent_id)->first();
            $job = PriceParentDiscount::updateOrCreate(
                [
                    'parent_id' => $parent_id,
                ],
                [
                    'discount_amount' => $discount_amount,
                    'discount_type' => $discount_type,
                    'online_amount' => $discount_amount,
                    'online_type' => $discount_type,
                ]
            );

            $comment = 'Parent discount added for ' . ($discount_type == 'fixed' ? '\$' : '\%') . $discount_amount;
            if (!empty($prev_job) && ($discount_type !== $prev_job->discount_type && $discount_amount !== $prev_job->discount_amount)) {
                $comment = 'Updated parent discount for ' . ($discount_type == 'fixed' ? '\$' : '\%') . $discount_amount;
            }
            $this->addJobHistory([
                'job_id' => $parent_id,
                'author' => auth()->user()->admin->admin_name,
                'comment' => $comment
            ]);
        } else {
            PriceParentDiscount::where('parent_id', $parent_id)->delete();
        }
    }

    /**
     * get all jobs according to the tutor.
     * @param mixed $tutor_id
     * @return array: Job
     */
    public function getAllJobs($tutor_id)
    {
        $tutor = Tutor::find($tutor_id);
        if (empty($tutor)) return [];

        $under_18 = $tutor->under18 ?? false;
        $experienced_limit = $this->getOption('experience-limit') ?? 50;

        $tutor_lat = $tutor->lat ?? 0;
        $tutor_lon = $tutor->lon ?? 0;

        $job_status_value = $this->getOption('job-status') ?? false;
        if (!$job_status_value) {
            return [];
        }

        $query = Job::where('hidden', 0);
        if (!$tutor->online_acceptable_status) $query = $query->whereNot('session_type_id', 2);
        if ($under_18) $query = $query->whereNot('session_type_id', 1);

        $temp_jobs = $query->whereIn('job_status', [0, 3])->get();
        $jobs = [];
        if (!empty($temp_jobs)) {
            foreach ($temp_jobs as $job) {
                $ignored_tutors = $this->getIgnoredTutorsForJob($job->id);
                if (!empty($ignored_tutors) && in_array($tutor->id, $ignored_tutors)) continue;

                if (!!$job->vaccinated && $job->session_type_id == 1 && !$tutor->vaccinated) continue;

                if (empty($job->date)) continue;

                if ($job->job_status == 3) {
                    $waiting_leads_offers_count = WaitingLeadOffer::where('status', 0)->where('job_id', $job->id)->count();
                    if ($waiting_leads_offers_count > 0) continue;
                }

                $job->create_time = \DateTime::createFromFormat('d/m/Y H:i', $job->create_time)->format('Y-m-d H:i');

                $child = $job->child;
                if (!empty($child)) {
                    $parent = $child->parent;
                    if ($parent->parent_state != $tutor->state) continue;

                    $child_lat = $parent->parent_lat ?? 0;
                    $child_lon = $parent->parent_lon ?? 0;
                    $distance = $this->calcDistance($child_lat, $child_lon, $tutor_lat, $tutor_lon);
                    $job->distance = number_format($distance, 2, '.', '');

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

                    $rejected_jobs = RejectedJob::where('tutor_id', $tutor->id)->first();
                    if (!empty($rejected_jobs)) {
                        $rejected_exp = explode(',', $rejected_jobs->job_ids);
                        if (in_array($job->id, $rejected_exp)) continue;
                    }

                    $job->coords = [
                        'lat' => $job->parent->parent_lat ?? 0,
                        'lon' => $job->parent->parent_lon ?? 0
                    ];

                    $can_accept_job = true;
                    $can_accept_job_reason = '';
                    if (!$tutor->have_wwcc) {
                        $can_accept_job == false;
                        $can_accept_job_reason = "You do not have a valid Working With Children Check or application number on file, and can therefore not accept jobs.";
                    }
                    if (!$tutor->accept_job_status) {
                        $can_accept_job == false;
                        $can_accept_job_reason = "Please get in touch via live chat if you wish to work with a student listed here.";
                    }
                    if ($job->experienced_tutor && !$tutor->experienced) {
                        $can_accept_job == false;
                        $can_accept_job_reason = "You need to complete " . $experienced_limit . " lessons to view the details of this job opportunity.";
                    }
                    if (!empty($job->gender) && $job->gender != $tutor->gender) {
                        $can_accept_job == false;
                        $can_accept_job_reason = $job->gender . " tutor only.";
                    }

                    $job->can_accept_job = $can_accept_job;
                    $job->can_accept_job_reason = $can_accept_job_reason;
                }
                $jobs[] = $job;
            }
        }

        return $jobs;
    }

    /**
     * get query for finding tutors in find-tutor page
     * @param array $search_input : [state => 'NSW', subjects => ['aaa', 'bbb'], suburb => '2083',]
     * @return Builder
     */
    public function findTutorQuery($search_input)
    {
        $query =  Tutor::query()
            ->where('tutor_status', 1);
        // dd($search_input['state']);
        if (!empty($search_input['state'])) $query = $query->where('state', $search_input['state']);
        if (!empty($search_input['subjects'])) $query = $query->whereIn('expert_sub', $search_input['subjects']);
        if (!empty($search_input['gender'])) $query = $query->where('gender', $search_input['gender']);
        if (!empty($search_input['vaccinated'])) $query = $query->where('vaccinated', $search_input['vaccinated']);
        if (!empty($search_input['experienced'])) $query = $query->where('experienced', $search_input['experienced']);
        if (!empty($search_input['seeking_students'])) $query = $query->where('seeking_students', $search_input['seeking_students']);
        if (!empty($search_input['non_metro_tutors'])) $query = $query->where('non_metro', $search_input['non_metro_tutors']);
        if (!empty($search_input['availabilities'])) {
            $av_str = $this->generateBookingAvailability($search_input['availabilities']);
            $av_arr = explode(',', $av_str);
            foreach ($av_arr as $av) {
                $query = $query->where('availabilities', 'like', '%' . $av . '%');
            }
        }

        return $query;
    }

    /**
     * Order array from mon - sun
     * @param ['tue-6:30 PM', 'mon-7:00 AM'...]
     * @return ['mon-7:00 AM', 'tue-6:30 PM'...]
     */
    public function orderAvailabilitiesAccordingToDay($avail_arr)
    {
        $total_availabilities = Availability::get();
        $orderedAvailabilities = [];
        foreach ($total_availabilities as $item) {
            foreach ($item->getAvailabilitiesName() as $ele) {
                $avail_hour = $item->short_name . '-' . $ele;
                if (in_array($avail_hour, $avail_arr)) $orderedAvailabilities[] = $avail_hour;
            }
        }
        return $orderedAvailabilities;
    }

    /**
     * add job_id to tutor offer volume.
     * @param mixed $tutor_id
     * @param mixed $job_id
     * @return void
     */
    public function addJobVolumeOffer($tutor_id, $job_id)
    {
        $job_volume_count = JobVolumeCount::where('job_id', $job_id)->where('tutor_id', $tutor_id)->first();
        if (empty($job_volume_count)) {
            JobVolumeCount::create([
                'job_id' => $job_id,
                'tutor_id' => $tutor_id,
                'date' => (new \DateTime('now'))->format('d/m/Y H:i')
            ]);
            Tutor::where('id', $tutor_id)->increment('break_count');
        }

        $tutor_offer_volume = TutorOfferVolume::where('tutor_id')->first();
        $job_ids = $job_id;
        $offers = 1;
        if (!empty($tutor_offer_volume)) {
            if (!in_array($job_id, $tutor_offer_volume->job_ids_array)) {
                $job_ids = $tutor_offer_volume->job_ids . ";" . $job_id;
                $offers = $tutor_offer_volume->offers + 1;
                $tutor_offer_volume->update([
                    'job_ids' => $job_ids,
                    'offers' => $offers,
                    'date_lastupdate' => (new \DateTime('now'))->format('d/m/Y H:i')
                ]);
            }
        } else {
            TutorOfferVolume::create([
                'tutor_id' => $tutor_id,
                'job_ids' => $job_id,
                'offers' => 1,
                'date_lastupdate' => (new \DateTime('now'))->format('d/m/Y H:i')
            ]);
        }
    }
    /**
     * Upsert job offer
     * @param int $job_id
     * @param array $diff ;['time' => 86400, 'amount' => 5]
     * @return void
     */
    public function upsertJobOffer($job_id, $diff) {
        $job_offer = JobOffer::where('job_id', $job_id)->first();
        if (empty($job_offer) || !empty($job_offer) && $job_offer->expiry == 'permanent') {
            JobOffer::updateOrCreate([
                'job_id' => $job_id,
            ], [
                'offer_amount' => $diff['amount'],
                'offer_type' => 'fixed',
                'expiry' => 'permanent'
            ]);

            $time = $diff['time'] / 60 / 60;
            $this->addJobHistory([
                'job_id' => $job_id,
                'comment' => 'Added \$' . $diff['amount'] . ' offer after ' . $time . ' hours'
            ]);
        }
    }
}
