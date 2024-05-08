<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasFactory;

    protected $table = 'news_post';

    protected $fillable = [
        'user_id',
        'content',
        'file',
        'tagged_tutor',
        'allow',
        'type',
        'pin',
    ];
}
