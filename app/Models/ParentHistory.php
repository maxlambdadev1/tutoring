<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent_history';

    protected $fillable = [
        'parent_id',
        'author',
        'comment',
        'date',
    ];
}
