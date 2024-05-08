<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTutorOffer extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_tutor_offer';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'offer_amount',
        'online_offer_amount',
        'offer_type',
    ];
}
