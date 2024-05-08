<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelineDds extends Model
{
    use HasFactory;

    protected $table = 'alchemy_pipeline_dds';

    protected $fillable = [
        'first_name',
        'email',
        'status',
        'date_added',
    ];
}
