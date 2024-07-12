<?php

namespace App\Livewire\Tutor;

use setasign\Fpdi\Tfpdf\Fpdi;
use Illuminate\Support\Facades\Storage;
use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('tutor.layouts.app')]
class Payments extends Component
{

    public function getFinancialDetails($year)
    {
        try {
            if (empty($year)) throw new \Exception('select the year');

            $date1 = '01/07/' . $year;
            $date2 = '30/06/' . ((int)$year + 1);
            $tutor = auth()->user()->tutor;

            $res = Session::where('session_status', 2)
                ->whereNotNull('session_charge_time')
                ->where('tutor_id', $tutor->id)
                ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $date1 . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) >=0")
                ->whereRaw("TIMESTAMPDIFF(SECOND, '" . $date2 . "', DATE_FORMAT(STR_TO_DATE(session_date, '%d/%m/%Y'), '%Y-%m-%d')) <=0")
                ->selectRaw('SUM(session_tutor_price*session_length) as tutor_total_price, SUM(session_penalty) as tutor_total_penalty')
                ->first();

            $pdf = new Fpdi();
            $pdf->AddPage();

            $logo_path = storage_path('app/public/logo_black.jpg');
            $pdf->Image($logo_path, 150, 25, 40, 20);
            $current_x = 20;
            $font_family = 'Arial';

            $pdf->SetFont($font_family, '', 20);
            $pdf->setXY($current_x, 65);
            $pdf->Write(0, 'Financial Year Summary Of Payments');

            $pdf->SetFont($font_family, '', 16);
            $pdf->setXY($current_x, 80);
            $pdf->Write(0, 'From the 1st year of July ' . $year . ' to the 30th of June ' . ((int)($year) + 1));

            $pdf->SetFont($font_family, '', 14);
            $pdf->setXY($current_x, 95);
            $pdf->Write(0, 'Payments received from:');
            $pdf->setXY($current_x, 102);
            $pdf->Write(0, 'Elite Education Australia Pty Ltd T/as Alchemy Tuition');
            $pdf->setXY($current_x, 109);
            $pdf->Write(0, 'ABN: 88606073367');
            $pdf->setXY($current_x, 116);
            $pdf->Write(0, 'Level 36/1 Farrer Pl, Sydney NSW 2000');
            $pdf->setXY($current_x, 123);
            $pdf->Write(0, '1300914329');
            $pdf->setXY($current_x, 130);
            $pdf->Write(0, 'info@alchemytuition.com.au');

            $pdf->setXY($current_x, 145);
            $pdf->Write(0, 'Payments made to:');
            $pdf->setXY($current_x, 152);
            $pdf->Write(0, $tutor->tutor_name);
            $pdf->setXY($current_x, 159);
            $pdf->Write(0, $tutor->ABN);
            $pdf->setXY($current_x, 166);
            $pdf->Write(0, $tutor->address . ' ' . $tutor->suburb . ' ' . $tutor->tutor_state);
            $pdf->setXY($current_x, 173);
            $pdf->Write(0, $tutor->tutor_phone);
            $pdf->setXY($current_x, 180);
            $pdf->Write(0, $tutor->tutor_email);

            $pdf->setXY($current_x, 195);
            $pdf->Write(0, "Total payments received by contractor during financial year: $" . ($res->tutor_total_price - $res->tutor_total_penalty));
            $pdf->setXY($current_x, 210);
            $pdf->MultiCell(170, 6, "All payments made for services rendered as a contractor. No taxes were withheld.");
            $pdf->setXY($current_x, 230);
            $pdf->Write(0, "Note to tutor:");
            $pdf->setXY($current_x, 237);
            $pdf->MultiCell(170, 6, "A full breakdown of all payments received can be found in your tutor dashboard under Payments and can be downloaded as a spreadsheet by clicking the CSV button at the top of the page.");

            $file_name = 'report.pdf';
            $temp_path = tempnam(sys_get_temp_dir(), $file_name);
            $pdf->Output($temp_path, 'F');

            // Use Livewire's download response to allow downloading the file
            return response()->download($temp_path, $file_name)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            $this->dispatch('showToastrMessage', [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tutor.payments');
    }
}
