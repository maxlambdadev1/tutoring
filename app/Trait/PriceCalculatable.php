<?php

namespace App\Trait;
use App\Models\SessionType;
use App\Models\PriceTutor;
use App\Models\PriceTutorIncrease;
use App\Models\PriceParent;
use App\Models\PriceParentDiscount;
use App\Models\PriceTutorOffer;
use App\Models\SessionProgressReport;

trait PriceCalculatable
{
    /**
     * calcuate session price for parent.
     */
    public function calcSessionPrice($parentId, $sessionTypeId = 1) {
        $parentPriceRow = PriceParent::find($parentId);
        if (!$parentPriceRow) {
            PriceParent::create([
                'parent_id' => $parentId,
                'f2f' => SessionType::find(1)->session_price,
                'online' => SessionType::find(2)->session_price,
            ]);
            return $this->calcSessionPrice($parentId, $sessionTypeId);
        }

        $priceType = $sessionTypeId == 1 ? 'f2f' : 'online';
        $sessionPrice = $parentPriceRow->$priceType;
        $parentDiscountRow = PriceParentDiscount::where('parent_id', $parentId)->first();

        if ($parentDiscountRow) {
            switch ($parentDiscountRow->discount_type) {
                case 'fixed':
                    return round($sessionPrice - $parentDiscountRow->discount_amount, 2);
                case 'percentage':
                    return round($sessionPrice - ($parentDiscountRow->discount_amount * $sessionPrice / 100), 2);
                default:
                    return $sessionPrice;
            }
        }

        return $sessionPrice;
    }

    /**
     * calculate tutor price
     */
    public function calcTutorPrice($tutorId, $parentId, $childId, $sessionTypeId = 1) {
        $refDates = [
            1 => new \DateTime('2023-01-11'), // Reference date for f2f price increase
            2 => new \DateTime('2022-02-01')  // Reference date for online price increase
        ];

        $tutorPriceRow = PriceTutor::getInstance($tutorId, $parentId, $childId);
        if (!$tutorPriceRow) {
            PriceTutor::create([
                'tutor_id' => $tutorId,
                'parent_id' => $parentId,
                'child_id' => $childId,
                'f2f' => SessionType::find(1)->tutor_price,
                'online' => SessionType::find(2)->tutor_price,
            ]);
            return $this->calcTutorPrice($tutorId, $parentId, $childId, $sessionTypeId);
        }

        $priceType = $sessionTypeId == 1 ? 'f2f' : 'online';
        $tutorPrice = $tutorPriceRow->$priceType;
        $refDate = $refDates[$sessionTypeId];
        $increaseRate = SessionType::find($sessionTypeId)->increase_rate;

        $increaseAmountRow = PriceTutorIncrease::getInstance($tutorId, $parentId, $childId);
        $increaseAmount = $increaseAmountRow ? ($sessionTypeId == 1 ? $increaseAmountRow->increase_amount : $increaseAmountRow->online_increase_amount) : 0;

        $tutorOfferAmountRow = PriceTutorOffer::getInstance($tutorId, $parentId, $childId);
        $tutorOfferAmount = $tutorOfferAmountRow ? $tutorOfferAmountRow->offer_amount : 0;

        if ($tutorPriceRow->created_at < $refDate && $increaseAmount == 0) {
            $increaseAmount = $sessionTypeId == 1 ? 2 : 3;
            $increaseRate = $sessionTypeId == 1 ? 7.145 : 7;
            $tutorOfferAmount = $sessionTypeId == 1 ? 0 : $tutorOfferAmount;
        }

        $finalPrice = $tutorPrice + ($increaseAmount * $increaseRate * $tutorPrice / 100);
        if ($tutorOfferAmountRow) {
            $finalPrice += $this->applyOffer($tutorOfferAmountRow, $tutorPrice, $tutorOfferAmount);
        }

        return round($finalPrice, 2);
    }

    private function applyOffer($tutorOfferAmountRow, $tutorPrice, $tutorOfferAmount) {
        switch ($tutorOfferAmountRow->offer_type) {
            case 'fixed':
                return $tutorOfferAmount;
            case 'percentage':
                return ($tutorOfferAmount * $tutorPrice / 100);
            default:
                return 0;
        }
    }

    /**
     * create or update TutorPriceOffer
     */
    public function addTutorPriceOffer($tutor_id, $parent_id, $child_id, $offer_amount, $offer_type) {
        PriceTutorOffer::updateOrCreate([
            'tutor_id' => $tutor_id,
            'parent_id' => $parent_id,
            'child_id' => $child_id,
        ], [
            'offer_amount' => $offer_amount,
            'offer_type' => $offer_type
        ]);
    }
    /**
     * get tutor price from session_types table according to session_type_id
     * @param $session_type_id : 1 or 2
     */
    public function getCoreTutorPrice($session_type_id) {
        return SessionType::find($session_type_id)->tutor_price;
    }
    /**
     * get session price from session_types table according to session_type_id
     * @param $session_type_id : 1 or 2
     */
    public function getCoreSessionPrice($session_type_id) {
        return SessionType::find($session_type_id)->session_price;
    }
    /**
     * update tutor price increasement accoding to session progress reports. 
     * @param mixed $tutor_id
     * @param mixed $parent_id
     * @param mixed $child_id
     * @return void
     */
    public function addTutorPriceIncrease($tutor_id, $parent_id, $child_id) {
        $increase = 1;
        $online_increase = 1;
        $ref_date = new \DateTime('2022-02-01');

        $price_tutor = PriceTutor::getInstance($tutor_id, $parent_id, $child_id);
        $created_date = new \DateTime($price_tutor->created_at);
        $price_tutor_increase = PriceTutorIncrease::getInstance($tutor_id, $parent_id, $child_id);
        if (!empty($price_tutor_increase->increase_amount)) {
            if (($price_tutor_increase->increase_amount < 5)) {
                $last_report = SessionProgressReport::where('tutor_id', $tutor_id)->where('parent_id', $parent_id)->where('child_id', $child_id)->orderBy('session_count', 'DESC')->first();
                if (!empty($last_report)) {
                    if ($last_report->session_count == 10) {
                        $increase = 1;
                        $online_increase = 1;
                        if ($created_date < $ref_date) $online_increase = 3;
                    } else if ($last_report->session_count == 20) {
                        $increase = 2;
                        $online_increase = 2;
                        if ($created_date < $ref_date) $online_increase = 3;
                    } else if ($last_report->session_count == 30) {
                        $increase = 3;
                        $online_increase = 3;
                    } else if ($last_report->session_count == 40) {
                        $increase = 4;
                        $online_increase = 4;
                    } else if ($last_report->session_count == 50) {
                        $increase = 5;
                        $online_increase = 5;
                    }

                    PriceTutorIncrease::updateOrCreate([
                        'tutor_id' => $tutor_id,
                        'parent_id' => $parent_id,
                        'child_id' => $child_id,
                        'increase_amount' => $increase,
                        'online_increase_amount' => $online_increase,
                    ]);
                }                
            }
        } else {            
            if ($created_date < $ref_date) {
                $online_increase = 3;
            }
            PriceTutorIncrease::create([
                'tutor_id' => $tutor_id,
                'parent_id' => $parent_id,
                'child_id' => $child_id,
                'increase_amount' => $increase,
                'online_increase_amount' => $online_increase,
            ]);
        }
    }
}
