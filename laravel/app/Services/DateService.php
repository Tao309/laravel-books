<?php

namespace App\Services;

class DateService
{
    public function getDateTime(?string $dateTime): \DateTime
    {
        $date = new \DateTime($dateTime);
        $date->setTimezone($this->getCurrentDateTimeZone());
        $date->modify('-3 hours');

        return $date;
    }

    public function getCurrentDateTimeZone(): \DateTimeZone
    {
        return new \DateTimeZone('Europe/Moscow');
    }
}
