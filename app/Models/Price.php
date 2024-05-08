<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'alchemy_prices';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'subject',
        'price',
    ];
}
