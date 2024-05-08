<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnsubscribeList extends Model
{
    use HasFactory;

    protected $table = 'alchemy_unsubscribe_list';

    protected $fillable = [
        'user_id',
        'options',
    ];
}
