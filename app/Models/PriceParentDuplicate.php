<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceParentDuplicate extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_parent_duplicate';

    protected $fillable = [
        'price',
    ];
}
