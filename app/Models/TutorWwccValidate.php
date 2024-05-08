<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorWwccValidate extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_wwcc_validate';

    protected $fillable = [
        'tutor_id',
        'timestamp',
        '4w_reminder',
        '5w_reminder',
        '6w_reminder',
    ];
}
