<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

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

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function parent_price()
    {
        return $this->hasOne(PriceParent::class, 'job_id');
    }

    public function tutor_price()
    {
        return $this->hasOne(PriceTutor::class, 'job_id');
    }

    public function tutor_price_offer()
    {
        return $this->hasOne(PriceTutorOffer::class, 'job_id');
    }

    public function alchemy_sessions()
    {
        return $this->hasMany(Session::class, 'job_id');
    }

}
