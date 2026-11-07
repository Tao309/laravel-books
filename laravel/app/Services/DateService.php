<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public static function getDateTime(?string $dateTime): \DateTime
    {
        $date = new \DateTime($dateTime);
        $date->setTimezone(self::getCurrentDateTimeZone());
        $date->modify('-3 hours');

        return $date;
    }

    public static function getCurrentDateTimeZone(): \DateTimeZone
    {
        return new \DateTimeZone('Europe/Moscow');
    }

    public static function createFromDate(?string $date, string $format = 'Y-m-d H:i:s'): ?string
    {
        return $date ? Carbon::createFromFormat($format, $date)->format('Y-m-d H:i:s') : null;
    }
}
