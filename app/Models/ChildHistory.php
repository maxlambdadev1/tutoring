<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildHistory extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_children_history';

    protected $fillable = [
        'child_id',
        'author',
        'comment',
        'date',
    ];
}
