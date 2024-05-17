<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Trait\ModelSelectable;

class PriceTutor extends Model
{
    use HasFactory, ModelSelectable;

    protected $table = 'alchemy_price_tutor';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'f2f',
        'online',
    ];
}
