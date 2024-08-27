<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TutorApplication extends Model
{
    use HasFactory;
    protected $table = 'alchemy_tutor_application';
    protected $guarded = [];

    public function history() {
        return $this->hasMany(TutorApplicationHistory::class, 'application_id');
    }
    
    public function reference() {
        return $this->hasMany(TutorApplicationReference::class, 'application_id');
    }

    public function reference_history() {
        return $this->hasMany(TutorApplicationReferenceHistory::class, 'application_id');
    }

    public function metro_postcode() {
        return $this->belongsTo(MetroPostcode::class, 'postcode', 'postcode');
    }

    public function application_status() {
        return $this->hasOne(TutorApplicationStatus::class, 'application_id');
    }

    public function getDateSubmittedAmpmAttribute() {
        return Carbon::createFromFormat('d/m/Y H:i', $this->date_submitted)->format('d/m/Y h:i A');
    }
    
    public function getTutorNameAttribute()
    {
        return ucfirst($this->tutor_first_name) . ' ' . ucfirst($this->tutor_last_name);
    }
}
