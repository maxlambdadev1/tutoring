<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Tutor;
use App\Models\Session;
use App\Models\PriceTutor;
use App\Models\PriceTutorOffer;
use App\Models\PriceTutorIncrease;
use App\Models\SessionProgressReport;

class AuditExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $start_date_str, $end_date_str;

    public function __construct($start_date_str, $end_date_str) {
        $this->start_date_str = $start_date_str;
        $this->end_date_str = $end_date_str;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = array();
        $result_item = array();
        $price_arr = array();

        $start_date = new \DateTIme('now');
        $end_date = new \DateTime('now');
        if (!empty($this->start_date_str)) $start_date = \DateTime::createFromFormat('d/m/Y', $this->start_date_str);
        else $start_date = \DateTime::createFromFormat('d/m/Y', '01/01/2017');

        if (!empty($this->end_date_str)) $end_date = \DateTime::createFromFormat('d/m/Y', $this->end_date_str);
        else {
            $start_date1 = \DateTime::createFromFormat('d/m/Y', $start_date->format('d/m/Y'));
            $end_date = \DateTime::createFromFormat('d/m/Y', $start_date1->modify('+'.(date('Y') - $start_date1->format('Y') + 1).' years')->format('d/m/Y'));
        }

        if ($start_date > $end_date) {
            $temp = $end_date;
            $end_date = $start_date;
            $start_date = $temp;
        }

        $tutors = Tutor::get();
        foreach ($tutors as $tutor) {
            $sessions = Session::where('tutor_id', $tutor->id)->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') >= STR_TO_DATE('" .$start_date->format('d/m/Y'). "', '%d/%m/%Y')")->whereRaw("STR_TO_DATE(session_date, '%d/%m/%Y') <= STR_TO_DATE('" .$end_date->format('d/m/Y'). "', '%d/%m/%Y')")->get();
            if (!empty($sessions) && count($sessions) > 0) {
                foreach ($sessions as $session) {
                    $session_count = Session::where('tutor_id', $session->tutor_id)->where('parent_id', $session->parent_id)->where('child_id', $session->child_id)->where('id', '<=', $session->id)->count();
                    $child = $session->child;
                    $parent = $session->parent;
                    $result_item = [
                        'tutor_id' => $tutor->id,
                        'tutor_name' => $tutor->tutor_name,
                        'parent_id' => $session->parent_id,
                        'parent_name' => !empty($parent) ? $parent->parent_first_name . ' ' . $parent->parent_last_name : '-',
                        'child_id' => $session->child_id,
                        'child_name' => $child->child_name ?? '-',
                        'session_id' => $session->id,
                        'sprice' => $session->session_price,
                        'session_price' => $session->session_price,
                        'tprice' => $session->session_tutor_price,
                        'tutor_price' => $session->session_tutor_price,
                        'session_date' => $session->session_date,
                        'session_time' => $session->session_time,
                        'session_charge' => $session->session_charge_id,
                        'session_discount' => '',
                        'tutor_offer' => '',
                        'tutor_increase' => '',
                        'actual_increase' => '',
                        'session_count' => $session_count
                    ];

                    if (empty($price_arr) || $price_arr['child_id'] != $session->child_id || $price_arr['tutor_id'] != $session->tutor_id) {
                        $price_parent = $parent->price_parent ?? null;
                        if (empty($price_parent)) $result_item['session_price'] = 'No price found for parent';
                        
                        $session_discount_row = [
                            'discount_type' => 'fixed',
                            'discount_amount' => 0
                        ];
                        $price_parent_discount = $parent->price_parent_discount ?? null;
                        if (!empty($price_parent_discount)) $session_discount_row = [
                            'discount_type' => $price_parent_discount->discount_type,
                            'discount_amount' => $price_parent_discount->discount_amount
                        ];

                        if (!empty($price_parent)) {
                            if ($session_discount_row['discount_type'] == 'fixed') $final_session_price = round($price_parent->f2f - $session_discount_row['discount_amount'], 2);
                            else $final_session_price = round($price_parent->f2f * (100 - $session_discount_row['discount_amount']) / 100, 2);
                        } else $final_session_price = '';

                        $price_tutor = PriceTutor::getInstance($tutor->id, $session->parent_id, $session->child_id);
                        if (empty($price_tutor)) $result_item['tutor_price'] = 'No price found for tutor';
                        $price_tutor_offer = PriceTutorOffer::getInstance($tutor->id, $session->parent_id, $session->child_id);
                        $tutor_offer = [
                            'offer_type' => 'fixed',
                            'offer_amount' => 0
                        ];
                        if (!empty($price_tutor_offer)) $tutor_offer = [
                            'offer_type' => $price_tutor_offer->offer_type,
                            'offer_amount' => $price_tutor_offer->offer_amount,
                        ];
                        $price_tutor_increase = PriceTutorIncrease::getInstance($tutor->id, $session->parent_id, $session->child_id)->increase_amount ?? 0;

                        $session_progress_reports = SessionProgressReport::getInstance($tutor->id, $session->parent_id, $session->child_id, false);
                        if (!empty($session_progress_reports) && count($session_progress_reports) > 0) {
                            $counter = 0;
                            foreach ($session_progress_reports as $report) {
                                if (!empty($report->q1) && !empty($report->q2) && !empty($report->q3) && !empty($report->q4)) $counter++;
                            }

                            if ($price_tutor_increase != $counter) {
                                $result_item['tutor_increase'] = $price_tutor_increase;
                                $result_item['actual_increase'] = $counter;
                            }
                        }

                        if (!empty($price_tutor)) {
                            if ($tutor_offer['offer_type'] == 'fixed') $final_tutor_price = round($price_tutor->f2f + $tutor_offer['offer_amount'] + (($price_tutor_increase * 7 * $price_tutor->f2f) / 100), 2);
                            else $final_tutor_price = round($price_tutor->f2f * (100 + $tutor_offer['offer_amount']) / 100 + (($price_tutor_increase * 7 * $price_tutor->f2f) / 100), 2);
                        } else $final_tutor_price = '';

                        $price_arr = [
                            'tutor_id' => $tutor->id,
                            'child_id' => $session->child_id,
                            'session_price' => $final_session_price,
                            'tutor_price' => $final_tutor_price
                        ];

                    }

                    if ($session->session_price != $price_arr['session_price'] || $session->session_tutor_price != $price_arr['tutor_price']) {
                        $result_item['session_price'] = $price_arr['session_price'];
                        $result_item['tutor_price'] = $price_arr['tutor_price'];
                        $result[] = $result_item;
                    }
                }
            }
        }

        return collect($result);
    }

    public function headings(): array {
        return [
            'Tutor ID',
            'Tutor name',
            'Parent ID',
            'Parent name',
            'Child ID',
            'Child name',
            'Session ID',
            'Session Price',
            'Actual session price',
            'Tutor Price',
            'Actual tutor price',
            'Session Date',
            'Session Time',
            'Session Charge ID',
            'Discount',
            'Offer',
            'Increase',
            'Actual Increase',
            'Session count',
        ];
    }
}
