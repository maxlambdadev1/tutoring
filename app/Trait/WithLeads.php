<?php

namespace App\Trait;

use Illuminate\Support\Facades\DB;
use App\Models\Tutor;
use App\Models\AlchemyParent;
use App\Models\BookingTarget;
use App\Models\Child;
use App\Models\State;
use App\Models\Grade;
use App\Models\User;
use App\Models\Job;
use App\Models\JobIgnore;
use App\Models\JobOffer;
use App\Models\ParentReferrer;
use App\Models\PriceParentDiscount;
use App\Models\SessionType;
use App\Trait\Functions;


trait WithLeads
{
    use Functions;

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
        $query = Tutor::where('first_name', 'like', '%' . $str . '%')->orWhere('last_name', 'like', '%' . $str . '%')->orderBy('first_name');
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
                ]
            );
            $parent->referral_code = strval($parent->id + 10000);
            $parent->save();

            $body_for_booking = "Parent name: " . ucwords($parent->parent_first_name) . " " . ucwords($parent->parent_last_name) . "  <br>Parent phone: " . ucwords($parent->parent_phone) . " <br>Parent email: " . ucwords($parent->parent_email) . "  <br>Referral: " . $inputData['referral'] . "  <br>State: " . $inputData['state'] . "  Address: " . $inputData['address'] . "  <br>Postcode: " . $inputData['postcode'] . "  <br>Session type: " . $session_type . "  <br>Google booking: " . $google_booking . " <br>";

            //check referral code for new user
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
                        // $this->sendEmail($params, "parent-referral-notification");
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
                $body_for_booking .= "Student name: " . $student_first_name . " " . $student_last_name . "  <br>School: " . $student['student_school'] . "  <br>Grade: " . $student['grade'] . "  <br>Subject: " . $student['subject'] . "  <br>Date: " . $date_for_body . "  <br>Notes: " . $student['notes'] . "  <br>Start date: " . $student['start_date'];

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
                    'thirdparty_org_id' => isset($inputData['thirdparty_org_id']) ? $inputData['thirdparty_org_id'] : null,
                ]);

                $param = array();
                $param['first_name'] = $parent->parent_first_name;
                $param['student_name'] = $child->child_first_name;
                $param['email'] = $parent->parent_email;
                if (isset($inputData['welcome_email']) && !empty($inputData['welcome_email'])) {
                    // $this->sendEmail($param,'parent-booking-email');
                } elseif (empty($inputData['lead_source'])) {
                    // $this->functions->send_email($param,'parent-booking-email');
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
                    JobOffer::create([
                        'job_id' => $job->id,
                        'offer_amount' => $hot_lead['offer'],
                        'offer_type' => 'fixed',
                        'expiry' => 'permanent'
                    ]);
                }
            }
            if (!empty($inputData['tutor_apply_offer'])) {
                if ($inputData['offer_valid'] == 'permanent') $valid = $inputData['offer_valid'];
                else {
                    $datetime->modify('+' . $inputData['offer_valid'] . '');
                    $valid = $datetime->getTimestamp();
                }

                JobOffer::updateOrCreate(
                    [
                        'job_id' => $job->id,
                    ],
                    [
                        'offer_amount' => $inputData['offer_amount'],
                        'offer_type' => $inputData['offer_type'],
                        'expiry' => $valid
                    ]
                );
            }

            //add parent discount
            if (!empty($inputData['parent_apply_discount'])) {
                PriceParentDiscount::create([
                    'parent_id' => $parent->id,
                    'discount_amount' => $inputData['discount_amount'],
                    'discount_type' => $inputData['discount_type'],
                    'online_amount' => $inputData['discount_amount'],
                    'online_type' => $inputData['discount_type'],
                ]);
            }

            //add/update mailchimp user


            $body_for_booking = "<p>" . $body_for_booking . "</p>";
            $param = array();
            $param['email'] = 'nadine.cook@alchemytuition.com.au';
            // $this->sendEmail($param, '', '', $body_for_booking, "New Booking (" .$parent->parent_email .")");

            if ($google_ads == 1) {
                $param['no_addbcc'] = 1;
                $param['email'] = 'matt.ahern@alchemytuition.com.au';
                // $this->sendEmail($param, '', '', $body_for_booking, "New Booking (".$parent->parent_email .")");
            }
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
}
