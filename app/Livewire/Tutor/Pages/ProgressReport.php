<?php

namespace App\Livewire\Tutor\Pages;

use App\Models\AlchemyParent;
use App\Models\SessionProgressReport;
use App\Trait\PriceCalculatable;
use App\Models\Session;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\Task;
use App\Trait\Functions;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class ProgressReport extends Component
{
    use Functions, PriceCalculatable, Mailable;

    public $parent_id;
    public $child_id;
    public $tutor_id;
    public $count;
    public $progress_id;
    public $session_type_id;
    public $parent;
    public $q1;
    public $q2;
    public $q3;
    public $q4;

    public function mount()
    {
        $url = request()->query('key') ?? '';
        $flag = false;
        if (!empty($url)) {
            $details = unserialize(base64_decode($url));
            if (!empty($details)) {
                $this->progress_id = $details['id'];
                $this->count = $details['count'];
                $this->parent_id = $details['parent_id'];
                $this->tutor_id = $details['tutor_id'];
                $this->child_id = $details['child_id'];
                $this->session_type_id = $details['session_type_id'] ?? 1;
                if (!empty($this->progress_id) && !empty($this->tutor_id) && !empty($this->parent_id) && !empty($this->child_id))  $flag = true;
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $this->progress_id = 123;
        // $this->count = 20;
        // $this->parent_id = 2895;
        // $this->tutor_id = 235;
        // $this->child_id = 3387;
        // $this->session_type_id = 1;

        $this->parent = AlchemyParent::find($this->parent_id);
    }

    public function submitReport() {
        try {
            if (empty($this->q1) || empty($this->q2) || empty($this->q3) || empty($this->q4)) throw new \Exception('Please input all data');

            $report = SessionProgressReport::find($this->progress_id);
            $report->update([
                'q1' => $this->q1,
                'q2' => $this->q2,
                'q3' => $this->q3,
                'q4' => $this->q4,
                'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $this->addTutorPriceIncrease($this->tutor_id, $this->parent_id, $this->child_id);
            $tutor_price = $this->calcTutorPrice($this->tutor_id, $this->parent_id, $this->child_id, $this->session_type_id);
            $session = Session::where('tutor_id', $this->tutor_id)->where('parent_id', $this->parent_id)->where('child_id', $this->child_id)
                ->orderBy('id', 'DESC')->first();
            $session->update([
                'session_tutor_price' => $tutor_price
            ]);
            $tutor = Tutor::find($this->tutor_id);
            $parent = AlchemyParent::find($this->parent_id);
            $child = Child::find($this->child_id);
            
            $this->addTutorHistory([
                'tutor_id' => $this->tutor_id,
                'comment' => "Tutor has completed a progress report for " . $child->child_name . "(" . $report->session_count . " lessons",
            ]);

            $ukey = base64_encode(serialize([
                'tutor_id' => $tutor->id,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'tutor_name' => $tutor->tutor_name,
                'child_name' => $child->first_name,
                'report_id' => $this->progress_id,
            ]));
            $link = $this->setRedirect("https://" . env('TUTOR') . "/tutor_review?key=" . $ukey);
            $params = [
                'studentname' => $child->first_name,
                'parentfirstname' => $parent->parent_first_name,
                'link' => $link,
                'email' => $parent->parent_email
            ];
            $this->sendEmail($params['email'], 'parent-tutor-review-email', $params);

            Task::where('tutor_id', $tutor->id)->where('task_subject', 'progress')->where('task_name', 'like', "%".$child->child_name."%")
                ->update(['task_completed' => 1]);
            
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
        return view('livewire.tutor.pages.progress-report');
    }
}
