<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDaily extends Model
{
    use HasFactory;

    protected $table = 'alchemy_report_daily';

    protected $fillable = [
        'date',
        'day',
        'bookings',
        'conversions',
        'tutor_conversions',
        'team_conversions',
        'total_confirmed_sessions',
        'confirmed_first_sessions',
        'total_confirmed_hours',
        'leads_in_system',
        'date_last_updated',
    ];
}
