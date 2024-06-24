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
/**
 * @param $smsParams=['name' => , 'phone' => ], $title : string, $params = ['a' => , 'b..' => ..], $type : true or false
 */
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

        // $response = Http::withToken(config('services.podium.key'))->post(config('services.podium.url'), $fields);
    }

    private function getMailTemplate($title)
    {
        return Option::where('option_name', $title)->first()->value;
    }

    private function getSmsTemplate($title)
    {
        return Option::where('option_name', $title)->first()->value;
    }

    private function getFilteredContent($content, $params)
    {
        if (isset($params['appid']))                $content = str_replace('%%appid%%', $params['appid'], $content);
        if (isset($params['attitude']))             $content = str_replace('%%attitude%%', $params['attitude'], $content);
        if (isset($params['adminfirstname']))       $content = str_replace('%%adminfirstname%%', $params['adminfirstname'], $content);
        if (isset($params['adminname']))            $content = str_replace('%%adminname%%', $params['adminname'], $content);
        if (isset($params['address']))              $content = str_replace('%%address%%', $params['address'], $content);
        if (isset($params['amount']))               $content = str_replace('%%amount%%', $params['amount'], $content);
        if (isset($params['cdate']))                $content = str_replace('%%cdate%%', $params['cdate'], $content);
        if (isset($params['currentyear']))          $content = str_replace('%%currentyear%%', $params['currentyear'], $content);
        if (isset($params['date']))                 $content = str_replace('%%date%%', $params['date'], $content);
        if (isset($params['email']))                $content = str_replace('%%email%%', $params['email'], $content);
        if (isset($params['grade']))                $content = str_replace('%%grade%%', $params['grade'], $content);
        if (isset($params['favourite']))            $content = str_replace('%%favourite%%', $params['favourite'], $content);
        if (isset($params['jobdate']))              $content = str_replace('%%jobdate%%', $params['jobdate'], $content);
        if (isset($params['length']))               $content = str_replace('%%length%%', $params['length'], $content);
        if (isset($params['link']))                 $content = str_replace('%%link%%', $params['link'], $content);
        if (isset($params['mainresult']))           $content = str_replace('%%mainresult%%', $params['mainresult'], $content);
        if (isset($params['mind']))                 $content = str_replace('%%mind%%', $params['mind'], $content);
        if (isset($params['notes']))                $content = str_replace('%%notes%%', $params['notes'], $content);
        if (isset($params['nextsessiondate']))      $content = str_replace('%%nextsessiondate%%', $params['nextsessiondate'], $content);
        if (isset($params['nextsessiontime']))      $content = str_replace('%%nextsessiontime%%', $params['nextsessiontime'], $content);
        if (isset($params['onlinesessionurl']))     $content = str_replace('%%onlinesessionurl%%', $params['onlinesessionurl'], $content);
        if (isset($params['onlineURL']))            $content = str_replace('%%onlineURL%%', $params['onlineURL'], $content);
        if (isset($params['parentname']))           $content = str_replace('%%parentname%%', $params['parentname'], $content);
        if (isset($params['parentfirstname']))      $content = str_replace('%%parentfirstname%%', $params['parentfirstname'], $content);
        if (isset($params['parentnumber']))         $content = str_replace('%%parentnumber%%', $params['parentnumber'], $content);
        if (isset($params['parentphone']))          $content = str_replace('%%parentphone%%', $params['parentphone'], $content);
        if (isset($params['performance']))          $content = str_replace('%%performance%%', $params['performance'], $content);
        if (isset($params['personality']))          $content = str_replace('%%personality%%', $params['personality'], $content);
        if (isset($params['q1']))                   $content = str_replace('%%q1%%', $params['q1'], $content);
        if (isset($params['q2']))                   $content = str_replace('%%q2%%', $params['q2'], $content);
        if (isset($params['q3']))                   $content = str_replace('%%q3%%', $params['q3'], $content);
        if (isset($params['q4']))                   $content = str_replace('%%q4%%', $params['q4'], $content);
        if (isset($params['reschedulejobdate']))    $content = str_replace('%%reschedulejobdate%%', $params['reschedulejobdate'], $content);
        if (isset($params['referencefirstname']))   $content = str_replace('%%referencefirstname%%', $params['referencefirstname'], $content);
        if (isset($params['reason']))               $content = str_replace('%%reason%%', $params['reason'], $content);
        if (isset($params['reasonlink']))           $content = str_replace('%%reasonlink%%', $params['reasonlink'], $content);
        if (isset($params['studentname']))          $content = str_replace('%%studentname%%', $params['studentname'], $content);
        if (isset($params['STUDENTNAME']))          $content = str_replace('%%STUDENTNAME%%', $params['STUDENTNAME'], $content);
        if (isset($params['studentfirstname']))     $content = str_replace('%%studentfirstname%%', $params['studentfirstname'], $content);
        if (isset($params['studentbirthday']))      $content = str_replace('%%studentbirthday%%', $params['studentbirthday'], $content);
        if (isset($params['sessiondate']))          $content = str_replace('%%sessiondate%%', $params['sessiondate'], $content);
        if (isset($params['sessiontime']))          $content = str_replace('%%sessiontime%%', $params['sessiontime'], $content);
        if (isset($params['sessiontype']))          $content = str_replace('%%sessiontype%%', $params['sessiontype'], $content);
        if (isset($params['sessionnotes']))         $content = str_replace('%%sessionnotes%%', $params['sessionnotes'], $content);
        if (isset($params['sessionprice']))         $content = str_replace('%%sessionprice%%', $params['sessionprice'], $content);
        if (isset($params['specialrequirement']))   $content = str_replace('%%specialrequirement%%', $params['specialrequirement'], $content);
        if (isset($params['subject']))              $content = str_replace('%%subject%%', $params['subject'], $content);
        if (isset($params['sdate']))                $content = str_replace('%%sdate%%', $params['sdate'], $content);
        if (isset($params['time']))                 $content = str_replace('%%time%%', $params['time'], $content);
        if (isset($params['tutorabn']))             $content = str_replace('%%tutorabn%%', $params['tutorabn'], $content);
        if (isset($params['tutoraddress']))         $content = str_replace('%%tutoraddress%%', $params['tutoraddress'], $content);
        if (isset($params['tutorfirstname']))       $content = str_replace('%%tutorfirstname%%', $params['tutorfirstname'], $content);
        if (isset($params['tutorname']))            $content = str_replace('%%tutorname%%', $params['tutorname'], $content);
        if (isset($params['tutorprice']))           $content = str_replace('%%tutorprice%%', $params['tutorprice'], $content);
        if (isset($params['tutorprofilelink']))     $content = str_replace('%%tutorprofilelink%%', $params['tutorprofilelink'], $content);
        if (isset($params['tutornumber']))          $content = str_replace('%%tutornumber%%', $params['tutornumber'], $content);
        if (isset($params['tutoremail']))           $content = str_replace('%%tutoremail%%', $params['tutoremail'], $content);
        if (isset($params['tutorphone']))           $content = str_replace('%%tutorphone%%', $params['tutorphone'], $content);
        if (isset($params['userfirstname']))        $content = str_replace('%%userfirstname%%', $params['userfirstname'], $content);
        if (isset($params['username']))             $content = str_replace('%%username%%', $params['username'], $content);
        if (isset($params['useremail']))            $content = str_replace('%%useremail%%', $params['useremail'], $content);
        if (isset($params['vouchernumber']))        $content = str_replace('%%vouchernumber%%', $params['vouchernumber'], $content);

        return $content;
    }

}
