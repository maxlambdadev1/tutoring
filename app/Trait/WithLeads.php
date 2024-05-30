<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\Tutor;
use App\Models\ReplacementTutor;
use App\Models\AlchemyParent;
use App\Models\BookingTarget;
use App\Models\Child;
use App\Models\TutorFirstSession;
use App\Models\Session;
use App\Models\User;
use App\Models\Job;
use App\Models\JobReschedule;
use App\Models\JobIgnore;
use App\Models\JobOffer;
use App\Models\ParentReferrer;
use App\Models\PriceParentDiscount;
use App\Models\SessionType;
use App\Models\ThirdpartyOrganisation;
use App\Models\WaitingLeadOffer;
use App\Trait\Functions;
use App\Trait\Mailable;
use Carbon\Carbon;
use PhpParser\Node\Stmt\TryCatch;

trait WithLeads
{
    use Functions, Mailable;

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
        $query = Tutor::where('tutor_name', 'like', '%' . $str . '%')->orderBy('tutor_name');
        if (empty($length)) {
            $tutors = $query->get();
        } else {
            $tutors = $query->limit($length)->get();
        }
        return $tutors;
    }

    public function searchParentsChildren($str, $length)
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

    public function searchParentAndChild($str, $length, $thirdparty_org_id)
    {
        $query = AlchemyParent::where('parent_first_name', 'like', '%' . $str . '%')->orWhere('parent_last_name', 'like', '%' . $str . '%')->orWhere('parent_email', 'like', '%' . $str . '%')
            ->orWhereHas('children', function ($query) use ($str) {
                $query->where('child_name', 'like', '%' . $str . '%');
            });
        if (!empty($thirdparty_org_id) && intval($thirdparty_org_id) > 0) {
            $query = $query::where('thirdparty_org_id', $thirdparty_org_id);
        }
        if (empty($length)) {
            $parents = $query->get();
        } else {
            $parents = $query->limit($length)->get();
        }
        return $parents;
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
                ['role' => 3]
            );

            $coords = $this->getCoord(urlencode($inputData['address']));
            if (empty($coords['lat'])) $coords['lat'] = 0;
            if (empty($coords['lon'])) $coords['lon'] = 0;
            $location = $coords['suburb'];
            $address_mailchimp = $coords['address'];

            $parent_existed = AlchemyParent::where('parent_email', $inputData['parent_email'])->count() > 0 ? true : false;

            $parent = AlchemyParent::updateOrCreate(
                ['parent_email' => $inputData['parent_email']],
                [
                    'parent_first_name' => ucwords($inputData['parent_first_name']),
                    'parent_last_name' => ucwords($inputData['parent_last_name']),
                    'parent_phone' => $inputData['parent_phone'],
                    'parent_state' => $inputData['state'],
                    'parent_address' => $coords['address'],
                    'parent_suburb' => $coords['suburb'],
                    'parent_postcode' => $inputData['postcode'],
                    'parent_lat' => $coords['lat'],
                    'parent_lon' => $coords['lon'],
                    'user_id' => $user->id,
                    'subscribe' => 1,
                    'thirdparty_org_id' => $thirdparty_org_id,
                ]
            );
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
     * @param $job_id, $tutor_id, $session_date : '23/05/2024', $session_time : '16:30' 
     */
    public function createSessionFromJob($job_id, $tutor_id, $session_date, $session_time, $converted_by = 'tutor')
    {
        try {
            $job = Job::find($job_id);
            $tutor = Tutor::find($tutor_id);

            $job->update([
                'job_status' => 1,
                'accepted_by' => $tutor->id,
                'last_updated' => date('d/m/Y H:i'),
                'accepted_on' => date('d/m/Y H:i'),
                'converted_by' => $converted_by,
            ]);

            $tutor->update([
                'break_count' => 0,
                'seeking_students' => 0,
                'seeking_students_timestamp' => time()
            ]);

            $datetime = new \DateTime('Australia/Sydney');
            if (!empty($job->job_offer)) {
                $job_offer = $job->job_offer;
                if ($job_offer->expiry == 'permanent' || $job_offer->expiry >= $datetime->getTimestamp()) {
                    $this->addTutorPriceOffer($tutor->id, $job->parent_id, $job->child_id, $job_offer->offer_amount, $job_offer->offer_type);
                }
            }

            if ($job->job_type == 'creative') {
                $session_price = 100;
                if ($job->session_type_id == 1) $tutor_price = 65;
                else $tutor_price = 50;
            } else {
                $session_price = $this->calcSessionPrice($job->parent_id, $job->session_type_id);
                $tutor_price = $this->calcTutorPrice($tutor->id, $job->parent_id, $job->child_id, $job->session_type_id);
            }
            $this->checkTutorFirstSession($tutor->id);

            $session_status = 3;
            $today = new \DateTime('now');
            $today->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            $ses_date = \DateTime::createFromFormat('d/m/Y H:i', $session_date . ' ' . $session_time);
            $ses_date->setTimeZone(new \DateTimeZone('Australia/Sydney'));
            if ($today->getTimestamp() >= $ses_date->getTimestamp()) $session_status = 1;

            $session = Session::create([
                'session_status' => $session_status,
                'tutor_id' => $tutor->id,
                'parent_id' => $job->parent_id,
                'child_id' => $job->child_id,
                'session_date' => $session_date,
                'session_time' => $session_time,
                'session_subject' => $job->subject,
                'session_is_first' => 1,
                'session_price' => $session_price,
                'session_tutor_price' => $tutor_price,
                'session_last_changed' => date('d/m/Y H:i'),
                'type_id' => $job->session_type_id
            ]);

            $parent = $job->parent;
            $child = $job->child;
            $params = [
                'firstname' => $tutor->first_name,
                'studentname' => $child->child_name,
                'studentfirstname' => $child->child_first_name,
                'grade' => $child->child_year,
                'date' => $session_date,
                'time' => $session_time,
                'subject' => $job->subject,
                'notes' => $job->job_notes,
                'mainresult' => $job->main_result ?? '-',
                'performance' => $job->performance ?? '-',
                'attitude' => $job->attitude ?? '-',
                'mind' => $job->mind ?? '-',
                'personality' => $job->personality ?? '-',
                'favourite' => $job->favourite ?? '-',
                'address' => $parent->parent_address . ', ' . $parent->parent_suburb . ', ' . $parent->parent_postcode,
                'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
                'parentphone' => $parent->parent_phone,
                'email' => $tutor->user->email,
                'tutoremail' => $tutor->user->email,
                'onlineURL' => $tutor->online_url
            ];

            if ($job->job_type == 'replacement' && !empty($job->replacement_id)) {
                $replacement = ReplacementTutor::find($job->replacement_id);
                if (!empty($replacement)) {
                    $replacement->update(['replacement_tutor_id' => $tutor_id]);
                    if ($job->session_type_id == 1) $this->sendEmail($tutor->user->email, 'tutor-first-session-details-replacement-email', $params);
                    else $this->sendEmail($tutor->user->email, 'tutor-first-online-session-details-email', $params);
                }
            } else {
                if ($job->job_type == 'creative') {
                    if ($job->session_type_id == 1) $this->sendEmail($tutor->user->email, 'tutor-creative-session-details-email', $params);
                    else $this->sendEmail($tutor->user->email, 'tutor-creative-online-session-details-email', $params);
                } else {
                    if ($job->session_type_id == 1) $this->sendEmail($tutor->user->email, 'tutor-first-session-details-email', $params);
                    else $this->sendEmail($tutor->user->email, 'tutor-first-online-session-details-email', $params);
                }
            }

            $smsParams = array(
                'phone' => $tutor->tutor_phone,
                'name' => $tutor->tutor_name
            );
            $params1 = [
                'tutorfirstname' => $tutor->first_name,
                'studentname' => $child->child_name,
                'sessiondate' => $session_date,
                'sessiontime' => $session_time
            ];
            if ($job->job_type == 'creative') $this->sendSms($smsParams, 'tutor-creative-session-details-sms', $params1);
            else $this->sendSms($smsParams, 'tutor-first-session-details-sms', $params1);

            $smsParams = array(
                'phone' => $parent->parent_phone,
                'name' => $parent->parent_name
            );
            $params1 = [
                'parentfirstname' => $parent->parent_first_name,
                'studentfirstname' => $child->child_first_name,
                'sessiondate' => $session_date,
                'sessiontime' => $session_time
            ];
            if ($job->job_type == 'creative') $this->sendSms($smsParams, 'parent-creative-session-details-sms', $params1);
            else $this->sendSms($smsParams, 'parent-first-session-detail-sms', $params1);

            $params['tutorname'] = $tutor->tutor_name;
            $params['tutorfirstname'] = $tutor->first_name;
            $params['parentfirstname'] = $parent->parent_first_name;
            $params['tutorphone'] = $tutor->tutor_phone;
            $params['price'] = $session_price;
            $params['email'] = $parent->user->email;
            $params['onlineurl'] = $tutor->online_url;
            $params['tutorlink'] = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/tutor/' . $tutor->id;

            if ($job->job_type == 'creative') {
                if ($job->session_type_id == 1) $this->sendEmail($parent->user->email, 'parent-creative-session-details-email', $params);
                else $this->sendEmail($parent->user->email, 'parent-creative-online-session-details-email', $params);
            } else {
                if ($job->session_type_id == 1) {
                    if (!empty($job->thirdparty_org)) $this->sendEmail($parent->user->email, 'third-party-first-lesson-details-f2f', $params);
                    else  $this->sendEmail($parent->user->email, 'parent-first-session-detail-email', $params);
                } else {
                    if (!empty($job->thirdparty_org)) $this->sendEmail($parent->user->email, 'third-party-first-lesson-details-online', $params);
                    else  $this->sendEmail($parent->user->email, 'parent-first-online-session-detail-email', $params);
                }
            }

            $this->deleteJobReschedule($job_id, $tutor->id);

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
                    'parentname' => $parent->parent_first_name . ' ' . $parent->parent_last_name,
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
                'author' => User::find(auth()->user()->id)->admin->admin_name,
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
            'author' => User::find(auth()->user()->id)->admin->admin_name,
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
                'author' => User::find(auth()->user()->id)->admin->admin_name,
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
                'author' => User::find(auth()->user()->id)->admin->admin_name,
                'comment' => $comment
            ]);
        } else {
            PriceParentDiscount::where('parent_id', $parent_id)->delete();
        }
    }
}
