<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildTest extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_children_test';

    protected $fillable = [
        'parent_id',
        'child_name',
        'child_first_name',
        'child_last_name',
        'child_year',
        'child_school',
        'child_status',
        'child_attitude',
        'child_confidence',
        'child_ability',
    ];
}
