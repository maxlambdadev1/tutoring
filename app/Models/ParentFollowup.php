<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentFollowup extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent_followup';

    protected $fillable = [
        'parent_id',
        'timestamp',
    ];
}
