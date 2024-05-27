<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Trait\Functions;

class Job extends Model
{
    use HasFactory, Functions;

    protected $table = 'alchemy_jobs';

    protected $guarded = [];



    /**
     * Get the session_type that owns the JobList
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session_type()
    {
        return $this->belongsTo(SessionType::class);
    }

    /**
     * Get the alchemy_parent that owns the JobList
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function alchemy_sessions()
    {
        return $this->hasMany(Session::class, 'job_id');
    }

    public function visited_tutors() {
        return $this->hasMany(JobVisit::class, 'job_id');
    }

    public function comments() {
        return $this->hasMany(JobHistory::class, 'job_id');
    }

    public function job_offer() {
        return $this->hasOne(JobOffer::class);
    }

    public function waiting_lead_offer() {
        return $this->hasMany(WaitingLeadOffer::class);
    }

    public function getAvailabilitiesAttribute() {
        return $this->getAvailabilitiesFromString($this->date);
    }

    public function getAvailabilities1Attribute() {
        return $this->getAvailabilitiesFromString1($this->date);
    }

}
