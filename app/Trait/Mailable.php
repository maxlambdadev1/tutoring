<?php

namespace App\Trait;

use App\Models\Option;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

trait Mailable
{
    /**
     * send template email according to $title when $params is not null, else send $customBody as email content
     * @param $sendTo : email address, $title: title, $params : array, $customBody : string
     */
    public function sendEmail($sendTo, $title, $params, $customBody = null)
    {
        if (empty($send_to)) return;
        if (!empty($title)) {
            if (!empty($params)) {
                $body = $this->getFilteredContent($this->getMailTemplate($title), $params);
                $subject = $this->getFilteredContent($this->getMailTemplate($title . '-subject'), $params);
            } else {
                $body = $customBody;
                $subject = $title;
            }
        }

        // Mail::to($sendTo)->send(new \App\Mail\MainMail($body, $subject));
    }
    /**
     * send template sms when template is not null, else send $title as sms content
     * @param $sms_params=['name' => , 'phone' => ], $title : string, $params = ['a' => , 'b..' => ..], $type : true or false
     */
    public function sendSms($sms_params, $title, $params = [], $type = false)
    {
        if (empty($sms_params['phone'])) return;
        if (empty($sms_params['name'])) $sms_params['name'] = 'Contact';
        if (strpos($sms_params['phone'], '+61') == false) $sms_params['phone'] = '+61' . $sms_params['phone'];

        $sms_params['locationUid'] = $type ? config('services.podium.locationUidSecond') : config('services.podium.locationUidFirst');
        $sms_params['body'] = $this->getFilteredContent($this->getSmsTemplate($title), $params);
        $fields = [
            'locationUid' => $sms_params['locationUid'],
            'customerName' => $sms_params['name'],
            'customerPhoneNumber' => $sms_params['phone'],
            'message' => $sms_params['body'],
        ];

        // $response = Http::withToken(config('services.podium.key'))->post(config('services.podium.url'), $fields);
    }

    private function getMailTemplate($title)
    {
        return Option::where('option_name', $title)->first()->value ?? '';
    }

    private function getSmsTemplate($title)
    {
        return Option::where('option_name', $title)->first()->value ?? $title;
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
        if (isset($params['chargedate']))           $content = str_replace('%%chargedate%%', $params['chargedate'], $content);
        if (isset($params['chargetime']))           $content = str_replace('%%chargetime%%', $params['chargetime'], $content);
        if (isset($params['date']))                 $content = str_replace('%%date%%', $params['date'], $content);
        if (isset($params['email']))                $content = str_replace('%%email%%', $params['email'], $content);
        if (isset($params['engagementrating']))     $content = str_replace('%%engagementrating%%', $params['engagementrating'], $content);
        if (isset($params['grade']))                $content = str_replace('%%grade%%', $params['grade'], $content);
        if (isset($params['favourite']))            $content = str_replace('%%favourite%%', $params['favourite'], $content);
        if (isset($params['feedback']))             $content = str_replace('%%feedback%%', $params['feedback'], $content);
        if (isset($params['feedbackurl']))          $content = str_replace('%%feedbackurl%%', $params['feedbackurl'], $content);
        if (isset($params['jobdate']))              $content = str_replace('%%jobdate%%', $params['jobdate'], $content);
        if (isset($params['lastsessiondate']))      $content = str_replace('%%lastsessiondate%%', $params['lastsessiondate'], $content);
        if (isset($params['length']))               $content = str_replace('%%length%%', $params['length'], $content);
        if (isset($params['link']))                 $content = str_replace('%%link%%', $params['link'], $content);
        if (isset($params['mainresult']))           $content = str_replace('%%mainresult%%', $params['mainresult'], $content);
        if (isset($params['mind']))                 $content = str_replace('%%mind%%', $params['mind'], $content);
        if (isset($params['notes']))                $content = str_replace('%%notes%%', $params['notes'], $content);
        if (isset($params['nolink']))               $content = str_replace('%%nolink%%', $params['nolink'], $content);
        if (isset($params['nextsessiondate']))      $content = str_replace('%%nextsessiondate%%', $params['nextsessiondate'], $content);
        if (isset($params['nextsessiontime']))      $content = str_replace('%%nextsessiontime%%', $params['nextsessiontime'], $content);
        if (isset($params['nextdate']))             $content = str_replace('%%nextdate%%', $params['nextdate'], $content);
        if (isset($params['nexttime']))             $content = str_replace('%%nexttime%%', $params['nexttime'], $content);
        if (isset($params['onlinesessionurl']))     $content = str_replace('%%onlinesessionurl%%', $params['onlinesessionurl'], $content);
        if (isset($params['onlinelimitnumber']))    $content = str_replace('%%onlinelimitnumber%%', $params['onlinelimitnumber'], $content);
        if (isset($params['onlineURL']))            $content = str_replace('%%onlineURL%%', $params['onlineURL'], $content);
        if (isset($params['onlineurl']))            $content = str_replace('%%onlineurl%%', $params['onlineurl'], $content);
        if (isset($params['overallrating']))        $content = str_replace('%%overallrating%%', $params['overallrating'], $content);
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
        if (isset($params['referralcode']))         $content = str_replace('%%referralcode%%', $params['referralcode'], $content);
        if (isset($params['referralprice']))        $content = str_replace('%%referralprice%%', $params['referralprice'], $content);
        if (isset($params['reschedulejobdate']))    $content = str_replace('%%reschedulejobdate%%', $params['reschedulejobdate'], $content);
        if (isset($params['referencefirstname']))   $content = str_replace('%%referencefirstname%%', $params['referencefirstname'], $content);
        if (isset($params['referralspecialprice'])) $content = str_replace('%%referralspecialprice%%', $params['referralspecialprice'], $content);
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
        if (isset($params['thirdpartyorgname']))    $content = str_replace('%%thirdpartyorgname%%', $params['thirdpartyorgname'], $content);
        if (isset($params['thirdpartyorgcontactfirstname']))  $content = str_replace('%%thirdpartyorgcontactfirstname%%', $params['thirdpartyorgcontactfirstname'], $content);
        if (isset($params['tutorabn']))             $content = str_replace('%%tutorabn%%', $params['tutorabn'], $content);
        if (isset($params['tutoraddress']))         $content = str_replace('%%tutoraddress%%', $params['tutoraddress'], $content);
        if (isset($params['tutorfirstname']))       $content = str_replace('%%tutorfirstname%%', $params['tutorfirstname'], $content);
        if (isset($params['tutorname']))            $content = str_replace('%%tutorname%%', $params['tutorname'], $content);
        if (isset($params['tutorprice']))           $content = str_replace('%%tutorprice%%', $params['tutorprice'], $content);
        if (isset($params['tutorprofilelink']))     $content = str_replace('%%tutorprofilelink%%', $params['tutorprofilelink'], $content);
        if (isset($params['tutornumber']))          $content = str_replace('%%tutornumber%%', $params['tutornumber'], $content);
        if (isset($params['tutornotes']))           $content = str_replace('%%tutornotes%%', $params['tutornotes'], $content);
        if (isset($params['tutoremail']))           $content = str_replace('%%tutoremail%%', $params['tutoremail'], $content);
        if (isset($params['tutorphone']))           $content = str_replace('%%tutorphone%%', $params['tutorphone'], $content);
        if (isset($params['userfirstname']))        $content = str_replace('%%userfirstname%%', $params['userfirstname'], $content);
        if (isset($params['understandingrating']))  $content = str_replace('%%understandingrating%%', $params['understandingrating'], $content);
        if (isset($params['username']))             $content = str_replace('%%username%%', $params['username'], $content);
        if (isset($params['useremail']))            $content = str_replace('%%useremail%%', $params['useremail'], $content);
        if (isset($params['vouchernumber']))        $content = str_replace('%%vouchernumber%%', $params['vouchernumber'], $content);

        return $content;
    }

    public function generateUniqueSms()
    {

        $entry_template = array(
            'Hi %%tutorfirstname%%! ',
            'Hey %%tutorfirstname%%! ',
            'Hi there %%tutorfirstname%%! ',
            'G\'day %%tutorfirstname%%! ',
            'Hey %%tutorfirstname%% - it\'s your lucky day! ',
            'Hi %%tutorfirstname%% - here\'s something exciting for you! ',
            'Hello %%tutorfirstname%%! ',
            'Hi %%tutorfirstname%%, today is your day! ',
            'Hey %%tutorfirstname%%, hope you have had a great week! ',
            'Hey %%tutorfirstname%% - here\'s one that we think you\'d be great for! ',
            'Hi %%tutorfirstname%%, hope you are living the dream! ',
            'Hi %%tutorfirstname%%, it\'s a great day to take on a new student! ',
            'Hi %%tutorfirstname%%, we\'ve got something exciting for you! ',
            'Hey %%tutorfirstname%%, you look amazing today! ',
            'Hey %%tutorfirstname%%, this one ticks all your boxes! ',
            'Howdy %%tutorfirstname%%! '
        );
        $mid_template = array(
            'We have a year %%grade%% student looking for help with %%subject%% in %%suburb%%. ',
            'We\'ve got a year %%grade%% student in %%suburb%% looking for help with %%subject%% that we think you\'d be perfect for! ',
            'There is a year %%grade%% student in %%suburb%% looking for support with %%subject%%. ',
            'We\'ve got a year %%grade%% student in %%suburb%% looking for help with %%subject%%. ',
            'We have a year %%grade%% student looking for help with %%subject%% in %%suburb%% with matching availabilities to you. ',
            'We have the perfect student for you - year %%grade%% in %%suburb%% looking for help with %%subject%%. ',
            'There is a new year %%grade%% student located in %%suburb%%, looking for help with %%subject%% that matches your availabilities! ',
            'We have a great student opportunity for you: year %%grade%% %%subject%% in %%suburb%%. ',
            'There is a year %%grade%% student looking for help with %%subject%% in %%suburb%%. ',
            'There\'s a new student near you: year %%grade%% %%subject%% in %%suburb%%. '
        );
        $end_template = array(
            'Learn more here: %%link%% ',
            'Check out the details here: %%link%%',
            'Take a look here: %%link%%',
            'View more and accept here: %%link%%',
            'Can you take them on? Learn more here: %%link%%',
            'Could you work with them? Learn more here: %%link%%',
            'Get started here: %%link%%',
            'What an opportunity! Check it here: %%link%%',
            'See the details here: %%link%%',
            'To view details and accept this student click here: %%link%%',
        );

        $sms_template = $entry_template[rand(0, (count($entry_template) - 1))] . $mid_template[rand(0, (count($mid_template) - 1))] . $end_template[rand(0, (count($end_template) - 1))];

        return $sms_template;
    }
}
