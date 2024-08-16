<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\Session;
use App\Models\TutorReview;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class Feedback extends Component
{
    public $comment;
    public $session;
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
                    $session_id = explode('=', $exp[1])[1] ?? '';
                    if (!empty($session_id)) {
                        $secret_origin = sha1($session_id . env('SHARED_SECRET'));
                        if ($secret == $secret_origin) {
                            $this->session = Session::find($session_id);
                            if (!empty($this->session)) $flag = true;
                        }
                    }
                }
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));

        // $this->session = Session::find(2345);
    }

    public function insertRegularReview($rating)
    {
        try {
            if (empty($this->comment) || empty($rating)) throw new \Exception('Please input all data');

            TutorReview::create([
                'tutor_id' => $this->session->tutor_id,
                'parent_id' => $this->session->parent_id,
                'child_id' => $this->session->child_id,
                'rating' => $rating,
                'rating_comment' => $this->comment,
                'type' => 0,
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
        return view('livewire.tutor.pages.feedback');
    }
}
