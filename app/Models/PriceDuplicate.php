<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceDuplicate extends Model
{
    use HasFactory;

    protected $table = 'alchemy_prices_duplicate';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'subject',
        'price',
    ];
}
