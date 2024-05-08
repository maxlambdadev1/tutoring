<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostcodeDb extends Model
{
    use HasFactory;

    protected $table = 'session_types';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];
}
