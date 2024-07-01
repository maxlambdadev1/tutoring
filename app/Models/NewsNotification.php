<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsNotification extends Model
{
    use HasFactory;

    protected $table = 'news_notification';
    protected $guarded = [];
    
}
