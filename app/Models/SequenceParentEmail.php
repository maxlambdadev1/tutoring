<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SequenceParentEmail extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sequence_parent_email';

    protected $fillable = [
        'subject',
        'content',
    ];
}
