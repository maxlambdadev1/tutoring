<?php

namespace App\Console\Commands;

use App\Models\Tutor;
use App\Models\TutorApplication;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorReferenceReminder extends Command
{
    use Functions, Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-reference-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reference emails to tutor application references after 2 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime('now'))->format('d/m/Y H:i');
        
        //for reference_reminder_48h == 0
        $applications = TutorApplication::query()
            ->join('alchemy_tutor_application_status', function ($status) {
                $status->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
            })
            ->where(function ($query) {
                $query->whereNot('application_status', 5)->orWhereNot('application_status', 6)->orWhereNot('application_status', 9);
            })
            ->whereNotNull('reference_update')
            ->whereNotNull('tutor_email1')
            ->whereNotNull('tutor_email2')
            ->where('reference_reminder_48h', 0)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE(reference_update, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i')) >= 172800")
            ->get();

        foreach ($applications as $application) {
            $references = $application->reference;
            $params = [
                'tutorname' => $application->tutor_name,
                'tutorfirstname' => $application->tutor_first_name,
                'referenceremindersubject' => "Reminder: Character reference for " . $application->tutor_name . " [ACTION REQUIRED]"
            ];
            $temp = [];
            if (!empty($references)) {
                foreach ($references as $reference) {
                    $temp[] = $reference->reference_email;
                }
            }
            if (empty($references) || count($temp) < 2) {
                if (empty($temp) || !in_array($application->tutor_email1, $temp)) {
                    $secret = sha1($application->tutor_email1 . env('SHARED_SECRET'));
                    $params['email'] = $application->tutor_email1;
                    $params['referencefirstname'] = $application->tutor_fname1;
                    $params['link'] = "https://" . env('TUTOR') . "/reference?url=" . base64_encode("secret=" . $secret . "&email=" . $application->tutor_email1 . "&application_id" . $application->id . "&reason=no");
                    $params['reasonlink'] = "https://" . env('TUTOR') . "/reference?url=" . base64_encode("secret=" . $secret . "&email=" . $application->tutor_email1 . "&application_id" . $application->id . "&reason=yes");
                    $this->sendEmail($params['email'], "tutor-application-reference-email", $params);
                }
                if (empty($temp) || !in_array($application->tutor_email2, $temp)) {
                    $secret = sha1($application->tutor_email2 . env('SHARED_SECRET'));
                    $params['email'] = $application->tutor_email2;
                    $params['referencefirstname'] = $application->tutor_fname2;
                    $params['link'] = "https://" . env('TUTOR') . "/reference?url=" . base64_encode("secret=" . $secret . "&email=" . $application->tutor_email2 . "&application_id" . $application->id . "&reason=no");
                    $params['reasonlink'] = "https://" . env('TUTOR') . "/reference?url=" . base64_encode("secret=" . $secret . "&email=" . $application->tutor_email2 . "&application_id" . $application->id . "&reason=yes");
                    $this->sendEmail($params['email'], "tutor-application-reference-email", $params);
                }
            }
            $application->update([
                'reference_reminder_48h' => 1,
                'reference_update' => $datetime,
            ]);
        }

        //for reference_reminder_48h == 1
        $applications = TutorApplication::query()
            ->join('alchemy_tutor_application_status', function ($status) {
                $status->on('alchemy_tutor_application.id', '=', 'alchemy_tutor_application_status.application_id');
            })
            ->where(function ($query) {
                $query->whereNot('application_status', 5)->orWhereNot('application_status', 6)->orWhereNot('application_status', 9);
            })
            ->whereNotNull('reference_update')
            ->whereNotNull('tutor_email1')
            ->whereNotNull('tutor_email2')
            ->where('reference_reminder_48h', 1)
            ->whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE(reference_update, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $datetime . "', '%d/%m/%Y %H:%i')) >= 172800")
            ->get();

        foreach ($applications as $application) {
            $references = $application->reference;
            $params = [
                'tutorname' => $application->tutor_name,
                'tutorfirstname' => $application->tutor_first_name,
                'referenceremindersubject' => "Reminder: Character reference for " . $application->tutor_name . " [ACTION REQUIRED]"
            ];
            $temp = [];
            if (!empty($references)) {
                foreach ($references as $reference) {
                    $temp[] = $reference->reference_email;
                }
                if (count($temp) < 2) {
                    if (!in_array($application->tutor_email1, $temp)) {
                        $secret = sha1($application->id . env('SHARED_SECRET'));
                        $params['email'] = $application->tutor_email;
                        $params['tutorfirstname'] = $application->tutor_first_name;
                        $params['link'] = "https://" . env('TUTOR') . "/reference-require?url=" . base64_encode("secret=" . $secret . "&application_id" . $application->id . "&number=1&column=reference1");
                        $this->sendEmail($params['email'], "tutor-reference-request-email", $params);
                    }
                    if (!in_array($application->tutor_email2, $temp)) {
                        $secret = sha1($application->id . env('SHARED_SECRET'));
                        $params['email'] = $application->tutor_email;
                        $params['tutorfirstname'] = $application->tutor_first_name;
                        $params['link'] = "https://" . env('TUTOR') . "/reference-require?url=" . base64_encode("secret=" . $secret . "&application_id" . $application->id . "&number=1&column=reference2");
                        $this->sendEmail($params['email'], "tutor-reference-request-email", $params);
                    }
                }
            } else {
                $secret = sha1($application->id . env('SHARED_SECRET'));
                $params['email'] = $application->tutor_email;
                $params['tutorfirstname'] = $application->tutor_first_name;
                $params['link'] = "https://" . env('TUTOR') . "/reference-require?url=" . base64_encode("secret=" . $secret . "&application_id" . $application->id . "&number=2&column=no");
                $this->sendEmail($params['email'], "tutor-reference-request-email", $params);
            }
            $application->increment('reference_reminder_96h');
            $application->update([
                'reference_update' => $datetime,
            ]);
        }
    }
}
