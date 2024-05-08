<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueUrl extends Model
{
    use HasFactory;

    protected $table = 'alchemy_unique_url';

    protected $fillable = [
        'long_url',
        'short_url',
        'date_created',
    ];
}
