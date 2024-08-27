<?php

namespace App\Console\Commands;

use App\Models\TutorOfferVolume;
use App\Models\Tutor;
use Illuminate\Support\Facades\DB;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class TutorJobsOfferVolume extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tutor-jobs-offer-volume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle Jobs offer volume';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = new \DateTime();

        $volumes = TutorOfferVolume::where('hidden', 0)->where('offers', '>=', 2)
            ->where('step_1', 0)->where('step_2',0)->where('step_3', 0)->get();
        foreach ($volumes as $volume) {
            $tutor = Tutor::query()
                ->leftJoin('alchemy_sessions', function($session) {
                    $session->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
                })
                ->where('tutor_id', $volume->tutor_id)
                ->having('total_sessions', '<', 1)
                ->groupBy('tutors.id')
                ->select('tutors.*',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'))
                ->first();

            if (empty($tutor)) continue;

            $params = [
                'email' => $tutor->tutor_email,
                'tutorfirstname' => $tutor->first_name,
            ];
            $this->sendEmail($params['email'], "tutor-offers-volume-step1-email", $params);
            $volume->update(['step_1' => 1]);
        }
        
        $volumes = TutorOfferVolume::where('hidden', 0)->where('offers', '>=', 4)
            ->where('step_1', 1)->where('step_2',0)->where('step_3', 0)->get();
        foreach ($volumes as $volume) {
            $tutor = Tutor::query()
                ->leftJoin('alchemy_sessions', function($session) {
                    $session->on('alchemy_sessions.tutor_id', '=', 'tutors.id');
                })
                ->where('tutor_id', $volume->tutor_id)
                ->having('total_sessions', '<', 1)
                ->groupBy('tutors.id')
                ->select('tutors.*',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'))
                ->first();

            if (empty($tutor)) continue;

            $params = [
                'email' => $tutor->tutor_email,
                'tutorfirstname' => $tutor->first_name,
            ];
            $this->sendEmail($params['email'], "tutor-offers-volume-step2-email", $params);
            $sms_params = [
                'name' => $tutor->tutor_name,
                'phone' => $tutor->tutor_phone
            ];
            $body = "Hi " . $tutor->first_name . ", it seems you are still yet to get started with your first student :( Could you take 30 seconds to let us know why and how we can better support you here: https://bit.ly/32WSOdH ";
            $this->sendSms($sms_params, $body);
            
            $volume->update(['step_2' => 1]);
        }
    }
}
