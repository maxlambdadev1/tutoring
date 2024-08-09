<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Trait\ModelSelectable;
use Carbon\Carbon;

class Session extends Model
{
    use HasFactory, ModelSelectable;

    protected $table = 'alchemy_sessions';
    protected $guarded = [];
    protected $appends = ['session_time_ampm'];

    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
    
    public function parent() {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child() {
        return $this->belongsTo(Child::class);
    }
    
    public function session_status_value() {
        return $this->belongsTo(SessionStatus::class, 'session_status');
    }
    
    public function prev_session() {
        return $this->belongsTo(self::class, 'session_previous_session_id');
    }
    
    public function history() {
        return $this->hasMany(SessionHistory::class);
    }
    
    public function failed_payment_history() {
        return $this->hasMany(FailedPaymentHistory::class);
    }

    public function getSessionTimeAmpmAttribute() {
        return Carbon::createFromFormat('H:i', $this->session_time)->format('h:i A');
    }
    public function getSessionDateYmdAttribute() {
        if (empty($this->session_date)) return '';
        else return Carbon::createFromFormat('d/m/Y', $this->session_date)->format('Y-m-d') ?? '';
    }
}
