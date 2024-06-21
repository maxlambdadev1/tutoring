<?php

namespace App\Models;

use App\Trait\ModelSelectable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionProgressReport extends Model
{
    use HasFactory, ModelSelectable;

    protected $table = 'alchemy_session_progress_report';
    protected $guarded = [];

    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
    
    public function parent() {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child() {
        return $this->belongsTo(Child::class);
    }
    
    public function session() {
        return $this->belongsTo(Session::class, 'last_session');
    }
    

}
