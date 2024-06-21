<?php

namespace App\Livewire\Admin\Payments;

use App\Models\PriceParentDiscount;
use App\Trait\WithLeads;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use App\Models\PriceTutor;
use App\Models\PriceTutorOffer;
use App\Models\PriceTutorIncrease;
use App\Models\SessionProgressReport;
use App\Trait\PriceCalculatable;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class EditPrices extends Component
{
    use WithLeads, PriceCalculatable;

    public $is_searching = false;
    public $search_str;
    public $searched_sessions = [];
    public $selected_session;
    public $prices = [];
    public $price_parent_discount = 0;
    public $price_tutor_offer = 0;
    public $price_tutor_increase = 0;
    public $tutor_increase_sync_input = false;

    public function searchStudentAndTutors1()
    {
        $this->searched_sessions = $this->searchStudentsFromSession($this->search_str, 10);
        $this->is_searching = false;
    }

    public function selectSession($ses_id)
    {
        $session = Session::find($ses_id);
        $this->selected_session = $session;
        if (!empty($session)) {
            $child = $session->child;
            $parent = $session->parent;
            $tutor = $session->tutor;

            $child_name = $child->child_name ?? '-';
            $parent_email = $parent->parent_email ?? '-';
            $tutor_email = $tutor->tutor_email ?? '-';
            $this->search_str = $child_name . '(Parent: ' . $parent_email . ')' . ' - Tutor: ' . $tutor_email;

            //get prices
            $result_item = [];
            $price_parent = $parent->price_parent ?? null;
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
            $counter = 0;
            if (!empty($session_progress_reports) && count($session_progress_reports) > 0) {
                foreach ($session_progress_reports as $report) {
                    if (!empty($report->q1) && !empty($report->q2) && !empty($report->q3) && !empty($report->q4)) $counter++;
                }
            }

            if (!empty($price_tutor)) {
                if ($tutor_offer['offer_type'] == 'fixed') $final_tutor_price = round($price_tutor->f2f + $tutor_offer['offer_amount'] + (($price_tutor_increase * 7 * $price_tutor->f2f) / 100), 2);
                else $final_tutor_price = round($price_tutor->f2f * (100 + $tutor_offer['offer_amount']) / 100 + (($price_tutor_increase * 7 * $price_tutor->f2f) / 100), 2);
            } else $final_tutor_price = '';

            $result_item['parent_price'] = $price_parent->f2f ?? '-';
            $result_item['parent_discount'] = $price_parent_discount->discount_amount ?? '-';
            $result_item['tutor_price'] = $price_tutor->f2f ?? '-';
            $result_item['tutor_offer'] = $price_tutor_offer->offer_amount ?? '-';
            $result_item['tutor_increase'] = $price_tutor_increase;
            $result_item['actual_increase'] = $counter;
            $result_item['final_parent_price'] = $final_session_price;
            $result_item['final_tutor_price'] = $final_tutor_price;

            $this->prices = $result_item;

            $this->price_parent_discount = $price_parent_discount->discount_amount ?? 0;
            $this->price_tutor_offer = $price_tutor_offer->offer_amount ?? 0;
            $this->price_tutor_increase = $price_tutor_increase;

        } else {
            $this->prices = [];
        }
    }

    public function updatePrice() {
        try {
            $session = $this->selected_session;
            if (!empty($session)) {

                PriceParentDiscount::updateOrCreate([
                    'parent_id' => $session->parent_id
                ],[
                    'discount_amount' => $this->price_parent_discount
                ]);

                PriceTutorOffer::updateOrCreate([
                    'tutor_id' => $session->tutor_id,
                    'child_id' => $session->child_id,
                    'parent_id' => $session->parent_id
                ], [
                    'offer_amount' => $this->price_tutor_offer,
                    'online_offer_amount' => 0
                ]);   
                
                $increase_row = PriceTutorIncrease::updateOrCreate([
                    'tutor_id' => $session->tutor_id,
                    'child_id' => $session->child_id,
                    'parent_id' => $session->parent_id
                ], [
                    'increase_amount' => $this->price_tutor_increase,
                    'online_increase_amount' => $this->price_tutor_increase
                ]);

                $last_session = Session::where('tutor_id', $session->tutor_id)->where('parent_id', $session->parent_id)->where('child_id', $session->child_id)->orderBy('id', 'desc')->first();

                $session_tutor_price = $this->calcTutorPrice($session->tutor_id, $session->parent_id, $session->child_id, $last_session->type_id ?? 1);
                $last_session->update([
                    'session_tutor_price' => $session_tutor_price
                ]);

                if (!empty($this->tutor_increase_sync_input)) {
                    $inc_array = array(
                        '10' => 0,
                        '20' => 0,
                        '30' => 0,
                        '40' => 0,
                        '50' => 0
                    );
                    $progress_row_copy = array(
                        'tutor_id' => $session->tutor_id,
                        'child_id' => $session->child_id,
                        'session_count' => '',
                        'last_session' => !empty($last_session)? $last_session->id : '1',
                        'unique_key' => '0',
                        'q1' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                        'q2' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                        'q3' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                        'q4' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
                        'review_reminder' => 1,
                        'reminder_count' => 3,
                        'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i')
                    );

                    if (!empty($increase_row)) {
                        $progress_reports = SessionProgressReport::getInstance($session->tutor_id, $session->parent_id, $session->child_id, false);
                        if (!empty($progress_reports) && count($progress_reports) > 0) {
                            foreach ($progress_reports as $progress_row) {
                                $progress_row_copy = $progress_row->toArray(); 
                                $inc_array[$progress_row['session_count']] = 1;
                            }
                        }

                        for($i = 1; $i <= $increase_row['increase_amount']; $i++) {
                            if (empty($inc_array[$i * 10])) SessionProgressReport::create([
                                'tutor_id' => $session->tutor_id,
                                'parent_id' => $session->parent_id,
                                'child_id' => $session->child_id,
                                'session_count' => $i * 10,
                                'last_session' => $progress_row_copy['last_session'],
                                'unique_key' => $progress_row_copy['unique_key'],
                                'q1' => $progress_row_copy['q1'],
                                'q2' => $progress_row_copy['q2'],
                                'q3' => $progress_row_copy['q3'],
                                'q4' => $progress_row_copy['q4'],
                                'review_reminder' => 1,
                                'reminder_count' => 3,
                                'date_lastupdated' => (new \DateTime('now'))->format('d/m/Y H:i')
                            ]);
                        }
                    }
                }

            } else throw new \Exception('Please select the data');

            return redirect()->back()->with('info', __('Price was updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.payments.edit-prices');
    }
}
