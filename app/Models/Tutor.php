<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    private $profile_path = 'uploads/tutor/profile';
    protected $guarded = [];
    protected $appends = ['first_name'];
    // protected $casts = [
    //     'availabilities' => 'array'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhoto()
    {
        return $this->photo ? $this->photo : 'images/no_avatar.png';
    }

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->tutor_name)[0];
    }
}
