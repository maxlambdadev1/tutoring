<?php

namespace App\Console\Commands;

use App\Models\GlassdoorReview as Review;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class GlassdoorReview extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:glassdoor-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send tutor-glassdoor-email to tutor when the date is after 2 days from now and delete the record.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $datetime = (new \DateTime())->format('d/m/Y H:i');

        $glassdoor_reviews = Review::whereRaw("TIMESTAMPDIFF(SECOND, STR_TO_DATE(date, '%d/%m/%Y %H:%i'), STR_TO_DATE('" . $datetime."', '%d/%m/%Y %H:%i')) >= 172800")->get();

        foreach ($glassdoor_reviews as $review) {
            $tutor = $review->tutor;

            $params = [
                'tutorfirstname' => $tutor->first_name,
                'email' => $tutor->tutor_email,
            ];
            $this->sendEmail($params['email'], "tutor-glassdoor-email", $params);
            $review->delete();
        }
    }
}
