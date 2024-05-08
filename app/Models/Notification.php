<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'alchemy_notifications';

    protected $fillable = [
        'user_id',
        'notification_text',
        'notification_level',
        'notification_seen',
    ];
}
