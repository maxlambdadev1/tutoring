<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentReferrer extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent_referrer';

    protected $fillable = [
        'parent_id',
        'referral_code',
        'first_lesson_confirmed_id',
        'first_lesson_credited_id',
    ];
}
