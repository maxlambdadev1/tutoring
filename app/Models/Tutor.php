<?php

namespace App\Models;

use App\Trait\Functions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes, Functions;

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
    
    public function history() {
        return $this->hasMany(TutorHistory::class);
    }

    public function application() {
        return $this->belongsTo(TutorApplication::class);
    }

    public function jobs_volume_offer() {
        return $this->hasMany(JobVolumeCount::class);
    }
    public function price_tutors() {
        return $this->hasMany(PriceTutor::class);
    }
            
    public function wwcc() {
        return $this->hasOne(TutorWwcc::class);
    }
    
    public function wwcc_validate() {
        return $this->hasOne(TutorWwccValidate::class);
    }
    
    public function getFirstNameAttribute()
    {
        return explode(' ', $this->tutor_name)[0];
    }
    
    public function getHaveWwccAttribute()
    {
        $then = \DateTime::createFromFormat('d/m/Y', $this->birthday);
        if(time() - $then->getTimestamp() >= 568025136){
            if (empty($this->wwcc_fullname) || empty($this->wwcc_number) || empty($this->wwcc_expiry)) {
                if (!empty($this->wwcc_application_number)) return false;
            }
        }
        return true;
    }
    
    public function getMatureAttribute()
    {
        $today = new \DateTime('now');
        $birth = \DateTime::createFromFormat('d/m/Y', $this->birthday);
        $interval = $today->diff($birth);
        if($interval->y >= 25) return true;
        
        return false;
    }
    
    public function getAvailabilities2Attribute() {
        return $this->getAvailabilitiesFromString($this->availabilities);
    }

    public function getAvailabilities1Attribute() {
        return $this->getAvailabilitiesFromString1($this->availabilities);
    }

}
