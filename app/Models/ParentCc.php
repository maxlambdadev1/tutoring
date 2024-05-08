<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentCc extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent_cc';

    protected $fillable = [
        'cc',
    ];
}
