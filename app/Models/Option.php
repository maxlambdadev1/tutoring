<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'alchemy_options';

    protected $fillable = [
        'option_name',
        'option_value',
    ];
}
