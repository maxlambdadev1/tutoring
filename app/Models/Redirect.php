<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $table = 'alchemy_redirect';

    protected $fillable = [
        'long_url',
        'short_url',
    ];
}
