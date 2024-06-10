<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionReschedule extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions_reschedule';

    protected $guarded = [];

    public function session() {
        return $this->belongsTo(Session::class);
    }

}
