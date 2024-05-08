<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetroPostcode extends Model
{
    use HasFactory;

    protected $table = 'metro_postcode';

    protected $fillable = [
        'postcode',
    ];
}
