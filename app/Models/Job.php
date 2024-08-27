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
        return $this->belongsTo(Tutor::class, 'accepted_by');
    }
    
    public function thirdparty_org()
    {
        return $this->belongsTo(ThirdpartyOrganisation::class);
    }

    public function alchemy_sessions()
    {
        return $this->hasMany(Session::class, 'job_id');
    }
    
    public function job_match()
    {
        return $this->hasOne(JobMatch::class, 'job_id');
    }

    public function visited_tutors() {
        return $this->hasMany(JobVisit::class, 'job_id');
    }

    public function history() {
        return $this->hasMany(JobHistory::class, 'job_id');
    }
    
    public function reschedule_details() {
        return $this->hasMany(JobReschedule::class, 'job_id');
    }
    
    public function reject_history() {
        return $this->hasMany(JobRejectHistory::class, 'job_id');
    }
    
    public function automation_history() {
        return $this->hasMany(JobAutomationHistory::class, 'job_id');
    }

    public function job_offer() {
        return $this->hasOne(JobOffer::class);
    }
    
    public function online_automation_limit() {
        return $this->hasOne(OnlineAutomationLimit::class);
    }

    public function waiting_lead_offer() {
        return $this->hasMany(WaitingLeadOffer::class);
    }

    public function getLastUpdatedTimestampAttribute() {
        $dtime = \DateTime::createFromFormat("d/m/Y H:i", $this->last_updated) ?? '';
        return $dtime->getTimestamp() ?? '';
    }

    public function getAvailabilitiesAttribute() {
        return $this->getAvailabilitiesFromString($this->date);
    }

    public function getAvailabilities1Attribute() {
        return $this->getAvailabilitiesFromString1($this->date);
    }

}
