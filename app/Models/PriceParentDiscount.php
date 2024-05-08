<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceParentDiscount extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_parent_discount';

    protected $fillable = [
        'parent_id',
        'discount_amount',
        'discount_type',
        'online_amount',
        'online_type',
    ];
}
