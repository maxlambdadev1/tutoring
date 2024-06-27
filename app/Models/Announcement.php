<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'alchemy_announcements';
    protected $guarded = [];  
    protected  $primaryKey = 'an_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'an_posted_by');
    }
}
