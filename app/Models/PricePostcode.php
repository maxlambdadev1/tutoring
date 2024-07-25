<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePostcode extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_postcode';
    protected $guarded = [];
    protected  $primaryKey = 'postcode';
    
}
