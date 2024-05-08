<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;

    protected $table = 'news_comment';

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'file',
        'allow',
    ];
}
