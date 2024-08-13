<?php

namespace App\Livewire\Tutor\Pages;

use setasign\Fpdi\Tfpdf\Fpdi;
use App\Models\Tutor;
use App\Models\Child;
use App\Models\TutorReview as TutorReviewModel;
use App\Models\AlchemyParent;
use App\Models\SessionProgressReport;
use App\Trait\Mailable;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.main')]
class TutorReview extends Component
{
    use Mailable;

    public $parent_id;
    public $child_id;
    public $tutor_id;
    public $count;
    public $progress_id;
    public $tutor;
    public $child;
    public $comment;

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
                if (!empty($this->progress_id) && !empty($this->tutor_id) && !empty($this->parent_id) && !empty($this->child_id))  $flag = true;
            }
        }
        if (!$flag) $this->redirect(env('MAIN_SITE'));
        // $this->progress_id = 123;
        // $this->count = 20;
        // $this->parent_id = 2895;
        // $this->tutor_id = 1062;
        // $this->child_id = 3387;

        $this->tutor = Tutor::find($this->tutor_id);
        $this->child = Child::find($this->child_id);
    }

    public function submitReport($rating)
    {
        try {
            if (empty($this->comment) || empty($rating)) throw new \Exception('Please input all data');

            $tutor_review = TutorReviewModel::create([
                'tutor_id' => $this->tutor_id,
                'parent_id' => $this->parent_id,
                'child_id' => $this->child_id,
                'progress_report_id' => $this->progress_id,
                'rating' => $rating,
                'rating_comment' => $this->comment,
                'type' => 1,
                'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i'),
            ]);

            $report = SessionProgressReport::find($this->progress_id);
            if (!empty($report)) {
                $report->update(['review_reminder' => 1]);

                $tutor = Tutor::find($this->tutor_id);
                $parent = AlchemyParent::find($this->parent_id);
                $child = Child::find($this->child_id);

                $params = [
                    'q1' => $report->q1,
                    'q2' => $report->q2,
                    'q3' => $report->q3,
                    'q4' => $report->q4,
                    'studentfirstname' => $child->first_name,
                    'parentfirstname' => $parent->parent_first_name,
                    'email' => $parent->parent_email,
                ];
                
                $pdf = new Fpdi();
                $template_path = storage_path("app/files/progressreportblank.pdf");
                $page_count = $pdf->setSourceFile($template_path); 
                for ($page_no = 1; $page_no <= $page_count; $page_no++) {
                    $pdf->AddPage();
                    $template = $pdf->importPage($page_no);
                    $pdf->useTemplate($template);
                    $pdf->SetFont('Helvetica');
                    $pdf->SetMargins(20,1,20);
                    $pdf->SetAutoPageBreak(false);
                    
                    if ($page_no == 1) {
                        $pdf->SetFont('Helvetica', 'B');
                        $pdf->SetFontSize(36);
                        $pdf->SetXY(19, 130);
                        $pdf->Write(2, $child->child_name);
                    }
                    if ($page_no == 2) {
                        $pdf->SetFontSize(12);
                        $pdf->SetXY(30, 59.5);
                        $pdf->Write(2, $parent->parent_first_name);

                        $pdf->SetFontSize(8);
                        $pdf->SetY(-25);
                        $pdf->Cell(0, 10, 'Progress report for ' . $child->child_name , 0, 0, 'C');
                    }
                    if ($page_no == 3) {
                        $pdf->SetFontSize(14);
                        $pdf->SetXY(20, 49.5);
                        $pdf->Write(10, $report->q1);
                        $pdf->SetXY(20, 180);
                        $pdf->Write(10, $report->q2);

                        $pdf->SetFontSize(8);
                        $pdf->SetY(-25);
                        $pdf->Cell(0, 10, 'Progress report for ' . $child->child_name , 0, 0, 'C');
                    }
                    if ($page_no == 4) {
                        $pdf->SetFontSize(14);
                        $pdf->SetXY(20, 34.5);
                        $pdf->Write(10, $report->q3);
                        $pdf->SetXY(20, 155);
                        $pdf->Write(10, $report->q4);

                        $pdf->SetFontSize(8);
                        $pdf->SetY(-25);
                        $pdf->Cell(0, 10, 'Progress report for ' . $child->child_name , 0, 0, 'C');
                    }
                    if ($page_no == 5) {
                        $pdf->SetFontSize(8);
                        $pdf->SetY(-25);
                        $pdf->Cell(0, 10, 'Progress report for ' . $child->child_name , 0, 0, 'C');
                    }
                }
                $file_name = $child->id .'_' . $parent->id . '_' . date('Y-m-d H-i') . '_progress_report.pdf';
                $file = storage_path('app/public/uploads/report/' . $file_name);
                $pdf->Output('F', $file);

                $this->sendEmail($params['email'], 'parent-progress-report-email', $params);
            }

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
        return view('livewire.tutor.pages.tutor-review');
    }
}
