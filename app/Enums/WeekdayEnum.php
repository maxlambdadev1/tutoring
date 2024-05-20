<?php

namespace App\Enums;

enum WeekdayEnum: string
{
    case Monday     = 'mon';
    case Tuesday    = 'tue';
    case Wednesday  = 'wed';
    case Thursday   = 'thu';
    case Friday     = 'fri';
    case Saturday   = 'sat';
    case Sunday     = 'sun';

    public function label(): string
    {
        return match ($this) {
            static::Monday      => 'Monday',
            static::Tuesday     => 'Tuesday',
            static::Wednesday   => 'Wednesday',
            static::Thursday    => 'Thursday',
            static::Friday      => 'Friday',
            static::Saturday    => 'Saturday',
            static::Sunday      => 'Sunday',
        };
    }
}
