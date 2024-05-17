<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Trait\ModelSelectable;

class PriceTutorIncrease extends Model
{
    use HasFactory, ModelSelectable;

    protected $table = 'alchemy_price_tutor_increase';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'increase_amount',
        'online_increase_amount',
    ];
}
