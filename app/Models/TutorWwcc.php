<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorWwcc extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_wwcc';

    protected $fillable = [
        'tutor_id',
        'verified_by',
        'verified_on',
    ];
}
