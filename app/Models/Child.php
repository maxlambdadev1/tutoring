<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_children';

    protected $fillable = [
        'parent_id',
        'child_name',
        'child_first_name',
        'child_last_name',
        'year',
        'child_birthday',
        'child_school',
        'child_status',
        'child_attitude',
        'child_confidence',
        'child_ability',
        'graduation_year',
        'google_ads',
        'follow_up',
        'no_follow_up_reason'
    ];
}
