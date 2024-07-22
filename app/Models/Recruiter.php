<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    use HasFactory;

    protected $table = 'alchemy_recruiter';

    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function history() {
        return $this->hasMany(RecruiterHistory::class);
    }
    
    public function getPhoto()
    {
        return  'images/no_avatar.png';
    }
}
