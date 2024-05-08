<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTutor extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_tutor';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'price',
        'online',
    ];
}
