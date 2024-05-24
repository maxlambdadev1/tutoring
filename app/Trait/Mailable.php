<?php

namespace App\Trait;

use App\Models\Option;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

trait Mailable
{
    public function sendEmail($sendTo, $title, $params)
    {
        if (empty($send_to)) return;
        if (!empty($title)) {
            $body = $this->getFilteredContent($this->getMailTemplate($title), $params);
            $subject = $this->getFilteredContent($this->getMailTemplate($title . '-subject'), $params);
        }

        // Mail::to($sendTo)->send(new \App\Mail\MainMail($body, $subject));
    }

    public function sendSms($smsParams, $title, $params, $type = false)
    {
        if (empty($smsParams['phone'])) return;
        if (empty($smsParams['name'])) $smsParams['name'] = 'Contact';
        if (strpos($smsParams['phone'], '+61') == false) $smsParams['phone'] = '+61' . $smsParams['phone'];

        $smsParams['locationUid'] = $type ? config('services.podium.locationUidSecond') : config('services.podium.locationUidFirst');
        $smsParams['body'] = $this->getFilteredContent($this->getSmsTemplate($title), $params);
        $fields = [
            'locationUid' => $smsParams['locationUid'],
            'customerName' => $smsParams['name'],
            'customerPhoneNumber' => $smsParams['phone'],
            'message' => $smsParams['body'],
        ];

        $response = Http::withToken(config('services.podium.key'))->post(config('services.podium.url'), $fields);
    }

    private function getMailTemplate($title)
    {
        return Option::where('option_name', $title)->first()->value;
    }

    private function getSmsTemplate($title)
    {

    }

    private function getFilteredContent($content, $params)
    {
        if (isset($params['address']))              $content = str_replace('%%address%%', $params['address'], $content);
        if (isset($params['grade']))                $content = str_replace('%%grade%%', $params['grade'], $content);
        if (isset($params['jobdate']))              $content = str_replace('%%jobdate%%', $params['jobdate'], $content);
        if (isset($params['onlinesessionurl']))     $content = str_replace('%%onlinesessionurl%%', $params['onlinesessionurl'], $content);
        if (isset($params['parentname']))           $content = str_replace('%%parentname%%', $params['parentname'], $content);
        if (isset($params['parentfirstname']))      $content = str_replace('%%parentfirstname%%', $params['parentfirstname'], $content);
        if (isset($params['parentnumber']))         $content = str_replace('%%parentnumber%%', $params['parentnumber'], $content);
        if (isset($params['reschedulejobdate']))    $content = str_replace('%%reschedulejobdate%%', $params['reschedulejobdate'], $content);
        if (isset($params['studentname']))          $content = str_replace('%%studentname%%', $params['studentname'], $content);
        if (isset($params['studentfirstname']))     $content = str_replace('%%studentfirstname%%', $params['studentfirstname'], $content);
        if (isset($params['studentbirthday']))      $content = str_replace('%%studentbirthday%%', $params['studentbirthday'], $content);
        if (isset($params['sessiondate']))          $content = str_replace('%%sessiondate%%', $params['sessiondate'], $content);
        if (isset($params['sessiontime']))          $content = str_replace('%%sessiontime%%', $params['sessiontime'], $content);
        if (isset($params['sessionnotes']))         $content = str_replace('%%sessionnotes%%', $params['sessionnotes'], $content);
        if (isset($params['sessionprice']))         $content = str_replace('%%sessionprice%%', $params['sessionprice'], $content);
        if (isset($params['subject']))              $content = str_replace('%%subject%%', $params['subject'], $content);
        if (isset($params['tutorfirstname']))       $content = str_replace('%%tutorfirstname%%', $params['tutorfirstname'], $content);
        if (isset($params['tutorprice']))           $content = str_replace('%%tutorprice%%', $params['tutorprice'], $content);
        if (isset($params['tutorprofilelink']))     $content = str_replace('%%tutorprofilelink%%', $params['tutorprofilelink'], $content);
        if (isset($params['tutorname']))            $content = str_replace('%%tutorname%%', $params['tutorname'], $content);
        if (isset($params['tutornumber']))          $content = str_replace('%%tutornumber%%', $params['tutornumber'], $content);
        if (isset($params['vouchernumber']))          $content = str_replace('%%vouchernumber%%', $params['vouchernumber'], $content);

        return $content;
    }
}
