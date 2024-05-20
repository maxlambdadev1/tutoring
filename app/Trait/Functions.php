<?php

namespace App\Trait;

use App\Models\Option;
use App\Models\JobHistory;
use App\Models\ChildHistory;

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
	);

	public const AMPM_SHORTCUT = array(
		'AM' => 'a',
		'PM' => 'p'
	);

	public const WEEK_DAYS = array(
		'mon' => 'Monday',
		'tue' => 'Tuesday',
		'wed' => 'Wednesday',
		'thu' => 'Thursday',
		'fri' => 'Friday',
		'sat' => 'Saturday',
		'sun' => 'Sunday',
	);

	public function getOption($option_name)
	{
		$option = Option::where('option_name', $option_name)->first();
		if (!empty($option)) {
			return $option->option_value;
		}
		return null;
	}

	public function getCoord($address)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geocodeObject = json_decode(curl_exec($ch));
		file_put_contents('coord.txt', print_r($geocodeObject, true));
		file_put_contents('coord_address.txt', print_r($address, true));
		curl_close($ch);

		// get latitude and longitude from geocode object
		$latitude = !empty($geocodeObject->results[0]->geometry->location->lat) ? $geocodeObject->results[0]->geometry->location->lat : 0;
		$longitude = !empty($geocodeObject->results[0]->geometry->location->lng) ? $geocodeObject->results[0]->geometry->location->lng : 0;
		if ($geocodeObject->results[0]->address_components[0]->types[0] == 'subpremise') {
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

	//mon-7:00 AM  =>  ma700
	//@return ma7,Sp730
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

	public function addJobHistory($post) {
		if (empty($post['author'])) $post['author'] = 'System';

		JobHistory::create([
			'job_id' => $post['job_id'],
			'author' => $post['author'],
			'comment' => $post['comment'],
			'date' => date('d/m/Y H:i')
		]);
	}
	public function addStudentHistory($post) {
		if (empty($post['author'])) $post['author'] = 'System';

		ChildHistory::create([
			'child_id' => $post['child_id'],
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
			}elseif($temp == 'Pre-K'){
				continue;
			}elseif($temp == 'K'){
				if ($oldest == 'Pre-K') {
					$oldest = $temp;
					continue;
				}else{
					continue;
				}
			}else{
				if ($temp > $oldest) {
					$oldest = $temp;
					continue;
				}else{
					continue;
				}
			}
		}
		return $oldest;
	}
}
