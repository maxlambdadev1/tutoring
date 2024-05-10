<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'grades' => 'array'
    ];
    protected $with = ['state'];
    
    /**
     * Get the state that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    public function getGradesName()
    {
        return collect($this->grades)->pluck('name')->join(', ', ' and ');
    }

    public function getGradesId()
    {
        return collect($this->grades)->pluck('id')->toArray();
    }
}
