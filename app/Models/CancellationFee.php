<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationFee extends Model
{
    use HasFactory;

    protected $table = 'alchemy_cancellation_fee';
    protected $guarded = [];

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

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function history()
    {
        return $this->hasMany(CancellationFeeHistory::class, 'cancellation_id');
    }
}
