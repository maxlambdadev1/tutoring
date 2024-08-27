<?php

namespace App\Console\Commands;

use App\Models\AlchemyParent;
use App\Models\Session;
use App\Models\SequenceParentEmail;
use App\Trait\Mailable;
use Illuminate\Console\Command;

class SequenceParent extends Command
{
    use Mailable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sequence-parent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $sessions = Session::query()
            ->join('alchemy_sessions as ses2', function ($ses2) {
                $ses2->on('alchemy_sessions.id', '=', 'ses2.session_previous_session_id')
                    ->on('alchemy_sessions.parent_id', '=', 'ses2.parent_id')
                    ->on('alchemy_sessions.tutor_id', '=', 'ses2.tutor_id')
                    ->on('alchemy_sessions.child_id', '=', 'ses2.child_id');
            })
            ->where('alchemy_sessions.session_is_first', 1)
            ->whereIn('ses2.session_status', [1,2,3])
            ->where(function ($query) {
                $query->whereNull('ses2.session_next_session_id')->orWhere('ses2.session_next_session_id', '');
            })
            ->whereRaw("DATEDIFF(curdate(), ses2.created_at) >= 1")
            ->get();
        foreach ($sessions as $session) {
            $parent = $session->parent;
            if ($parent->subscribe != 1 || $parent->sequence_email_id != 0) continue;

            $sequence = SequenceParentEmail::where('id', 1)->first();
            $title = $sequence->subject;
            $body = $sequence->content;
            $body = str_replace("%%parentfirstname%%", $parent->parent_first_name, $body);
            $body = str_replace("%%link%%", "https://" . env('TUTOR'). "/unsubscribe-parent?url=" . base64_encode('parent_email=' . $parent->parent_email), $body);
            $this->sendEmail($parent->parent_email, $title, null, $body);
            $parent->update([
                'sequence_email_id' => 1,
                'sequence_email_last_updated' => date('d/m/Y H:i'),
            ]);

        }

        $parents = AlchemyParent::where('subscribe', 1)->where('sequence_email_id', '>', 0)->whereRaw("DATEDIFF(curdate(), STR_TO_DATE(sequence_email_last_updated, '%d/%m/%Y %H:%i')) >= 3")->get();
        foreach ($parents as $parent) {
            $sequence = SequenceParentEmail::where('id', $parent->sequence_email_id)->first();
            if (!empty($sequence)) {
                $title = $sequence->subject;
                $body = $sequence->content;
                $body = str_replace("%%parentfirstname%%", $parent->parent_first_name, $body);
                $body = str_replace("%%link%%", "https://" . env('TUTOR'). "/unsubscribe-parent?url=" . base64_encode('parent_email=' . $parent->parent_email), $body);
                $this->sendEmail($parent->parent_email, $title, null, $body);
                $parent->update([
                    'sequence_email_id' => $sequence->id,
                    'sequence_email_last_updated' => date('d/m/Y H:i'),
                ]);
            }
        }
    }
}
