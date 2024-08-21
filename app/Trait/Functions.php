<?php

namespace App\Trait;

use App\Models\AlchemyParent;
use App\Models\CancellationFeeHistory;
use App\Models\Option;
use App\Models\UniqueUrl;
use App\Models\JobHistory;
use App\Models\ChildHistory;
use App\Models\SessionHistory;
use App\Models\JobAutomationHistory;
use App\Models\FailedPaymentHistory;
use App\Models\HolidayTutorHistory;
use App\Models\HolidayStudentHistory;
use App\Models\HolidayReplacementHistory;
use App\Models\MetroPostcode;
use App\Models\TutorHistory;
use App\Models\RecruiterHistory;
use App\Models\ParentHistory;
use App\Models\Tutor;
use App\Models\TutorApplicationHistory;
use App\Models\TutorApplicationReferenceHistory;
use App\Models\TutorSpecialReferralEmail;
use Carbon\Carbon;
use Carbon\CarbonInterval;

trait Functions
{
	public const DAY_SHOUTCUT = array(
		'mon' => 'm',
		'tue' => 't',
		'wed' => 'w',
		'thu' => 'T',
		'fri' => 'f',
		'sat' => 's',
		'sun' => 'S',
		'm' => 'mon',
		't' => 'tue',
		'w' => 'wed',
		'T' => 'thu',
		'f' => 'fri',
		's' => 'sat',
		'S' => 'sun',
	);

	public const AMPM_SHORTCUT = array(
		'AM' => 'a',
		'PM' => 'p',
		'a' => 'AM',
		'p' => 'PM'
	);

	public const WEEK_DAYS = array(
		'mon' => 'Monday',
		'tue' => 'Tuesday',
		'wed' => 'Wednesday',
		'thu' => 'Thursday',
		'fri' => 'Friday',
		'sat' => 'Saturday',
		'sun' => 'Sunday',
		'm' => 'Monday',
		't' => 'Tuesday',
		'w' => 'Wednesday',
		'T' => 'Thursday',
		'f' => 'Friday',
		's' => 'Saturday',
		'S' => 'Sunday',
	);

	public function getOption($option_name)
	{
		$option = Option::where('option_name', $option_name)->first();
		if (!empty($option)) {
			return $option->option_value;
		}
		return null;
	}

	/**
	 * @param $address : google address string
	 * @return array: ['lat' => 123.5212, 'lon' => 89.02, 'address' => string, 'suburb' => suburb, 'state' => state]
	 */
	public function getCoord($address)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geocodeObject = json_decode(curl_exec($ch));
		// file_put_contents('coord.txt', print_r($geocodeObject, true));
		// file_put_contents('coord_address.txt', print_r($address, true));
		curl_close($ch);

		// get latitude and longitude from geocode object
		$latitude = !empty($geocodeObject->results[0]->geometry->location->lat) ? $geocodeObject->results[0]->geometry->location->lat : 0;
		$longitude = !empty($geocodeObject->results[0]->geometry->location->lng) ? $geocodeObject->results[0]->geometry->location->lng : 0;
		$type = $geocodeObject->results[0]->address_components[0]->types[0] ?? '';
		if ($type == 'subpremise') {
			$add = (!empty($geocodeObject->results[0]->address_components[0]->short_name) ? $geocodeObject->results[0]->address_components[0]->short_name : '') . '/' . (!empty($geocodeObject->results[0]->address_components[1]->short_name) ? $geocodeObject->results[0]->address_components[1]->short_name : '') . ' ' . (!empty($geocodeObject->results[0]->address_components[2]->short_name) ? $geocodeObject->results[0]->address_components[2]->short_name : '');
			$suburb = !empty($geocodeObject->results[0]->address_components[3]->long_name) ? $geocodeObject->results[0]->address_components[3]->long_name : 0;
			$state = !empty($geocodeObject->results[0]->address_components[5]->short_name) ? $geocodeObject->results[0]->address_components[5]->short_name : 0;
		} else {
			$add = (!empty($geocodeObject->results[0]->address_components[0]->short_name) ? $geocodeObject->results[0]->address_components[0]->short_name : '') . ' ' . (!empty($geocodeObject->results[0]->address_components[1]->short_name) ? $geocodeObject->results[0]->address_components[1]->short_name : '');
			$suburb = !empty($geocodeObject->results[0]->address_components[2]->long_name) ? $geocodeObject->results[0]->address_components[2]->long_name : 0;
			$state = !empty($geocodeObject->results[0]->address_components[4]->short_name) ? $geocodeObject->results[0]->address_components[4]->short_name : 0;
		}

		return array(
			'lat' => $latitude,
			'lon' => $longitude,
			'address' => $add,
			'suburb' => $suburb,
			'state' => $state,
		);
	}

	/** 
	 *@param ['mon-7:00 AM','tue-5:30 PM','sun-6:00 PM'] 
	 *@return "ma7,tp530,Sp6"
	 */
	public function generateBookingAvailability($dates)
	{
		$return_array = array();
		foreach ($dates as $date) {
			if (empty($date)) continue;
			$date_split_array = explode('-', $date); //mon,  7:00 AM
			$day_shortcut = $this::DAY_SHOUTCUT[$date_split_array[0]]; //m
			$time_split_array = explode(' ', $date_split_array[1]); //7:00, AM
			$ampm_shoutcut = $this::AMPM_SHORTCUT[$time_split_array[1]]; //a
			$hour_split_array = explode(':', $date_split_array[1]); //7, 00
			$hour_shortcut = $hour_split_array[0] . (intval($hour_split_array[1]) > 0 ? intval($hour_split_array[1]) : '');  //7:00 => 7,  7:30 => 730

			array_push($return_array, $day_shortcut . $ampm_shoutcut . $hour_shortcut);
		}
		return implode(',', $return_array);
	}
	/**
	 * @param "ma7,tp530,Sp6"
	 * @return ['mon-7:00 AM','tue-5:30 PM','sun-6:00 PM'] 
	 */
	public function getAvailabilitiesFromString($str)
	{
		$availabilities = [];
		$str = trim($str, ',');
		$arr = explode(',', $str);
		if (!empty($arr) && count($arr) > 0) {
			foreach ($arr as $item) { //item : tp530...
				if (empty($item)) continue;
				$day = $this::DAY_SHOUTCUT[substr($item, 0, 1)]; //tue
				$ampm = $this::AMPM_SHORTCUT[substr($item, 1, 1)]; //
				$time = substr($item, 2); //530
				$hour = $time;
				$min = '00';
				$len = strlen($time);
				if ($len >= 3) {
					$hour = substr($time, 0, $len - 2); //5
					$min = substr($time, $len - 2);
				}
				$availabilities[] = $day . '-' . $hour . ':' . $min . ' ' . $ampm; //tue-5:30 PM
			}
		}
		return $availabilities;
	}

	/**
	 * @param $str : string "ma7,tp530,Sp6"
	 * @return array : ['Monday 7:00AM','Tuesday 5:30PM','Sunday 6:00PM'] 
	 */
	public function getAvailabilitiesFromString1($str)
	{
		$availabilities = [];
		$str = trim($str, ',');
		$arr = explode(',', $str);
		if (!empty($arr) && count($arr) > 0) {
			foreach ($arr as $item) { //item : tp530...
				$day = $this::WEEK_DAYS[substr($item, 0, 1)]; //tuesday
				$ampm = $this::AMPM_SHORTCUT[substr($item, 1, 1)]; //tue
				$time = substr($item, 2); //530
				$hour = $time;
				$min = '00';
				$len = strlen($time);
				if ($len >= 3) {
					$hour = substr($time, 0, $len - 2); //5
					$min = substr($time, $len - 2);
				}
				$availabilities[] = $day . ' ' . $hour . ':' . $min . '' . $ampm; //tue-5:30 PM
			}
		}
		return $availabilities;
	}

	/**
	 * generate session date and time from first day(ma7) of date_str. if type is true, time is G:i, else, time is g:iA
	 * @param $date_str: 'ma7,tp630',  
	 * @param $start_date: 'ASAP' or '23/05/2024'
	 * @param $type: true or false
	 * @return array : ['date' => '27/05/2023', 'time' => '19:30']
	 */
	public function generateSessionDate($date_str, $start_date, $type = true)
	{
		$av_date = explode(' ', $this->getAvailabilitiesFromString1($date_str)[0]); //['Monday', ''7:00AM']
		$datetime = new \DateTime('now');
		if (!empty($start_date)) {
			$datetime = $datetime->createFromFormat('d/m/Y', $start_date);
			if (!$datetime || strlen(explode('/', $start_date)[2]) != 4) {
				$datetime = new \DateTime('now');
			}
		}
		
		if (!$type) $time = $av_date[1]; //7:00PM
		else $time = Carbon::createFromFormat('g:iA', $av_date[1])->format('G:i'); //19:00

		return [
			'date' => $this->getNextDateByDay($datetime->format('d/m/Y'), $av_date[0])->format('d/m/Y'),
			'time' => $time
		];
	}

	/**
	 * @param $date = 23/05/2024, $day = 'Monday'
	 * @return \DateTime - '27/05/2024' - more than 2 days.
	 */
	public function getNextDateByDay($date, $day)
	{
		$datetime = new \DateTime('now');
		$curr_date = new \DateTime('now');
		$datetime = $datetime->createFromFormat('d/m/Y', $date);
		if ($datetime->format('l') != $day) {
			$datetime = $datetime->modify("next {$day}");
		}
		if (($datetime->getTimestamp() - $curr_date->getTimestamp()) < 172800) {
			$datetime = $this->getNextDateByDay($datetime->modify("next {$day}")->format('d/m/Y'), $day);
		}
		return $datetime;
	}

	/**
	 * @param ['mon-7:00 AM', 'tue-6:30 PM']
	 * @return ['Monday 7:00 AM', 'Tuesday 6:30 PM']
	 */
	function arrayFlatten($array)
	{
		$return = array();
		if (!is_array($array)) return $return;
		array_walk_recursive($array, function ($a) use (&$return) {
			//mon-7:00 AM   => Monday 7:00 AM
			foreach ($this::WEEK_DAYS as $key => $value) {
				$a = str_replace($key, $value, $a);
			}
			$a = str_replace("-", " ", $a);
			$return[] = $a;
		});
		return $return;
	}

	/**
	 * Get graduation year according to current grade year.
	 * @param int $child_year ; Pre-K, K, 1,2, 
	 * @param string $booking_day ; Y-m-d H:i:s
	 * @return int|null
	 */
	public function getGraduationYear($child_year, $booking_day = NULL)
	{
		if (empty($booking_day)) {
			$today = new \DateTime('now');
			$today->setTimeZone(new \DateTimeZone('Australia/Sydney'));
			$current_year = $today->format('Y');
			$current_month = (int)$today->format('m');
		} else {
			$booking_day = \DateTime::createFromFormat('Y-m-d H:i:s', $booking_day);
			$current_year = $booking_day->format('Y');
			$current_month = (int)$booking_day->format('m');
		}

		if ($child_year == 'Pre-K') {
			return (int)$current_year + 13;
		} elseif ($child_year == 'K') {
			return (int)$current_year + 12;
		} elseif ($child_year == '1') {
			return (int)$current_year + 11;
		} elseif ($child_year == '2') {
			return (int)$current_year + 10;
		} elseif ($child_year == '3') {
			return (int)$current_year + 9;
		} elseif ($child_year == '4') {
			return (int)$current_year + 8;
		} elseif ($child_year == '5') {
			return (int)$current_year + 7;
		} elseif ($child_year == '6') {
			return (int)$current_year + 6;
		} elseif ($child_year == '7') {
			return (int)$current_year + 5;
		} elseif ($child_year == '8') {
			return (int)$current_year + 4;
		} elseif ($child_year == '9') {
			return (int)$current_year + 3;
		} elseif ($child_year == '10') {
			return (int)$current_year + 2;
		} elseif ($child_year == '11') {
			return (int)$current_year + 1;
		} elseif ($child_year == '12') {
			if ($current_month >= 9) {
				return (int) $current_year + 1;
			} else {
				return (int)$current_year;
			}
		} else {
			return NULL;
		}
	}
	/**
	 * @param $post = ['job_id' => , 'author' =>, 'comment' =>]
	 */
	public function addJobHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		JobHistory::create([
			'job_id' => $post['job_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['job_id' => , 'author' =>, 'comment' =>]
	 */
	public function addJobAutomationHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		JobAutomationHistory::create([
			'job_id' => $post['job_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['child_id' => , 'author' =>, 'comment' =>]
	 */
	public function addStudentHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		ChildHistory::create([
			'child_id' => $post['child_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['parent_id' => , 'author' =>, 'comment' =>]
	 */
	public function addParentHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		ParentHistory::create([
			'parent_id' => $post['parent_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['tutor_id' => 123, 'author' => '', 'comment' => 'abcde']
	 */
	public function addTutorHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		TutorHistory::create([
			'tutor_id' => $post['tutor_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['application_id' => , 'author' =>, 'comment' =>]
	 */
	public function addTutorApplicationHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		TutorApplicationHistory::create([
			'application_id' => $post['application_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['holiday_id' => , 'author' =>, 'comment' =>]
	 */
	public function addHolidayTutorHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		HolidayTutorHistory::create([
			'holiday_id' => $post['holiday_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['holiday_id' => , 'author' =>, 'comment' =>]
	 */
	public function addHolidayStudentHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		HolidayStudentHistory::create([
			'holiday_id' => $post['holiday_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['holiday_id' => , 'author' =>, 'comment' =>]
	 */
	public function addHolidayReplacementHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		HolidayReplacementHistory::create([
			'holiday_id' => $post['holiday_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['application_id' => , 'author' =>, 'comment' =>]
	 */
	public function addTutorApplicationReferenceHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		TutorApplicationReferenceHistory::create([
			'application_id' => $post['application_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			// 'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['recruiter_id' => 123, 'author' => '', 'comment' => 'abcde']
	 */
	public function addRecruiterHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		RecruiterHistory::create([
			'recruiter_id' => $post['recruiter_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['session_id' => 123, 'author' => '', 'comment' => 'abcde']
	 */
	public function addSessionHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		SessionHistory::create([
			'session_id' => $post['session_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['session_id' => 123, 'author' => '', 'comment' => 'abcde']
	 */
	public function addFailedPaymentHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		FailedPaymentHistory::create([
			'session_id' => $post['session_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	/**
	 * @param $post = ['tutor_id' => 123, 'author' => '', 'comment' => 'abcde']
	 */
	public function addCancellationFeeHistory($post)
	{
		if (empty($post['author'])) $post['author'] = 'System';

		CancellationFeeHistory::create([
			'cancellation_id' => $post['cancellation_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}

	public function getOldestGrade($students)
	{
		$first = 'Pre-K';
		$second = 'K';
		$oldest = '';
		$temp = '';
		foreach ($students as $student) {
			if (!isset($student['grade'])) {
				continue;
			}
			$temp = $student['grade'];
			if ($oldest == '') {
				$oldest = $temp;
				continue;
			} elseif ($temp == 'Pre-K') {
				continue;
			} elseif ($temp == 'K') {
				if ($oldest == 'Pre-K') {
					$oldest = $temp;
					continue;
				} else {
					continue;
				}
			} else {
				if ($temp > $oldest) {
					$oldest = $temp;
					continue;
				} else {
					continue;
				}
			}
		}
		return $oldest;
	}

	/**
	 * get short url from long url for using as phone link
	 */
	public function setRedirect($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://a.lche.my');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "scope=set-redirect&url={$url}");
		$exec = curl_exec($ch);
		curl_close($ch);
		return $exec;
	}
	/**
	 * format seconds to string
	 * @param $sec : seconds ex: 12345
	 * @return string : ex: '3 weeks 2 days' or '3 days 5 hours'..
	 */
	public function formatSeconds($seconds) {
		// return CarbonInterval::seconds($seconds)->cascade()->forHumans();
		$seconds = (int)$seconds;
        if ( $seconds === 0 ) {
            return '0 secs';
        }
        // variables for holding values
        $mins  = 0;
        $hours = 0;
        $days  = 0;
        $weeks = 0;
        // calculations
        if ( $seconds >= 60 ) {
            $mins = (int)($seconds / 60);
            $seconds = $seconds % 60;
        }
        if ( $mins >= 60 ) {
            $hours = (int)($mins / 60);
            $mins = $mins % 60;
        }
        if ( $hours >= 24 ) {
            $days = (int)($hours / 24);
            $hours = $hours % 60;
        }
        if ( $days >= 7 ) {
            $weeks = (int)($days / 7);
            $days = $days % 7;
        }
        // format result
		$result = '';
		$counter = 0;
        if ( $weeks ) {
			$result .= "{$weeks} week(s) ";
			$counter++;
        }
        if ( $days && $counter < 2) {
			$result .= "{$days} day(s) ";
			$counter++;
        }
        if ( $hours && $counter < 2) {
			$result .= "{$hours} hour(s) ";
			$counter++;
        }
        if ( $mins && $counter < 2) {
			$result .= "{$mins} min ";
			$counter++;
        }
        if ( $seconds && $counter < 2) {
			$result .= "{$seconds} sec ";
			$counter++;
        }
        $result = rtrim($result);
        return $result;
	}
	/**
	 * @param $url:string
	 * @return $short_url
	 */
	public function generateUniqueUrl($url) {
        $short_url = substr(sha1(mt_rand()),17,6);
		UniqueUrl::create([
			'long_url' => $url,
			'short_url' => $short_url,
			'date_created' => date('d/m/Y H:i')
		]);
		return $short_url;
	}
	/**
	 * get distance from first position to second position
	 * @param $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo : float
	 * @return $distance : float
	 */
	public function calcDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
		$earthRadius = 6371000;

		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);
		
		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
		
		$angle = atan2(sqrt($a), $b);
		return number_format(($angle * $earthRadius)/1000, 2, '.', '');
	}

	/**
	 * check where the postcode exist or not.
	 * @param mixed $postcode
	 * @return bool
	 */
	public function checkMetro($postcode) {
		$metro_postcode = MetroPostcode::where('postcode', $postcode)->first();
		if (!empty($metro_postcode)) return true;
		else return false;
	}

	public function referralXeroBill($tutor, $referred_tutor_name, $special=0) {

	}

	/**
	 * return the difference hours of timezone in parent state and tutor state
	 * @param mixed $tutor_id
	 * @param mixed $parent_id
	 * @return float|int
	 */
	public function getTimezoneDiffHours($parent_id, $tutor_id)  {
		if (empty($parent_id) || empty($tutor_id)) return 0;
		$parent = AlchemyParent::find($parent_id);
		$tutor = Tutor::find($tutor_id);
		if (empty($parent) || empty($tutor)) return 0;

		$daylight = $this->getOption('daylight') ?? 0;
		$parent_state = $parent->parent_state ?? null;
		$tutor_state = $tutor->state ?? null;
		if (empty($tutor_state) || empty($parent_state) || empty($daylight)) return 0;

		if (!!$daylight) return $parent_state->summer - $tutor_state->summer;
		else return $parent_state->winter - $parent_state->winter;
	}

	/**
	 * return the calculated time based on hour difference. 
	 * @param string $time: '10:00 PM'
	 * @param float $hour_diff : 1.5
	 * @param bool $type : true or false
	 * @return string : '8:30 PM' or '11:30 PM'
	 */
	public function calculateTime($time, $hour_diff, $type = true) {		
        if ($type) $timestamp = strtotime($time) - $hour_diff * 60 * 60;
        else $timestamp = strtotime($time) + $hour_diff * 60 * 60;
        return date('g:i A', $timestamp);
	}

	/**
	 * return updated avaialbilities string based on timezone diff.
	 * @param mixed $av_str: 'mp9,Sa1130'
	 * @param mixed $hour_diff: 1.5
	 * @param mixed $type: true or false
	 * @return string :'mp730,Sa10'
	 */
	public function convertTimezone($av_str, $hour_diff, $type = true) {
		$av_arr = $this->getAvailabilitiesFromString($av_str);
		$arr = [];
		foreach ($av_arr as $item) {
			$day = explode('-', $item)[0]; //mon
			$time = explode('-', $item)[1]; //7:30 AM
			$converted_time = $this->calculateTime($time, $hour_diff, $type); //ex: 9:00 AM
			$arr[] = $day . '-'. $converted_time;
		}
		return $this->generateBookingAvailability($arr);
	}

	/**
	 * Validate Australia Business Number
	 */
	public function validateABN($abn)
	{
		$weights = array(10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19);

		// strip anything other than digits
		$abn = preg_replace("/[^\d]/","",$abn);

		// check length is 11 digits
		if (strlen($abn)==11) {
			// apply ato check method 
			$sum = 0;
			foreach ($weights as $position=>$weight) {
				$digit = $abn[$position] - ($position ? 0 : 1);
				$sum += $weight * $digit;
			}
			return ($sum % 89)==0;
		} 
		return false;
	}
}
