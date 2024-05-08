<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'alchemy_announcements';

    protected $fillable = [
        'an_text',
        'an_posted_by',
        'an_date',
        'an_time',
        'who',
        'flag'
    ];
}
