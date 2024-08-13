<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Tutor;
use App\Models\Child;
use App\Models\TutorReview;
use App\Models\AlchemyParent;
use App\Models\SessionProgressReport;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class TutorVote extends Component
{
    public $parent;
    public $tutor_id;
    public $comment;
    public $tutors = [];

    public function mount()
    {
        $url = request()->query('url') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = base64_decode($url);
            if (!empty($details)) {
                $exp = explode('&', $details);
                if (!empty($exp) && count($exp) >= 2) {
                    $secret = explode('=', $exp[0])[1] ?? '';
                    $parent_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($parent_id)) {
                        $secret_origin = sha1($parent_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $this->parent = AlchemyParent::find($parent_id);
                            if (!empty($this->parent)) $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $parent_id = 2889;
        // $this->parent = AlchemyParent::find($parent_id);
        
        $query = Tutor::query()
            ->leftJoin('alchemy_sessions', function ($session) {
                $session->on('tutors.id', '=', 'alchemy_sessions.tutor_id');
            })
            ->where('tutors.tutor_status', 1) 
            ->where('alchemy_sessions.session_status', 2)   
            ->whereRaw("TIMESTAMPDIFF(DAY, STR_TO_DATE(session_date, '%d/%m/%Y %H:%i'), STR_TO_DATE('".date("d/m/Y H:i")."', '%d/%m/%Y %H:%i')) <= 90")
            ->having('total_sessions', '>=', 2)
            ->groupBy('alchemy_sessions.tutor_id');

        $this->tutors = $query->select('tutors.*',  DB::raw('COUNT(alchemy_sessions.id) as total_sessions'))->get();

    }

    public function insertRegularReview() {
        try {
            if (empty($this->comment) || empty($this->tutor_id)) throw new \Exception('Please input all data');

            TutorReview::create([
                'tutor_id' => $this->tutor_id,
                'parent_id' => $this->parent->id,
                'rating_comment' => $this->comment,
                'type' => 2,
                'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            return true;
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            return false;
        }        
    }

    public function render()
    {
        return view('livewire.tutor.pages.tutor-vote');
    }
}
