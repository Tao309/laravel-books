<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class DateService
{
    public static function createFromDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            $parsed = Carbon::parse($date);
        } catch (\Throwable) {
            $parsed = null;

            foreach (['Y.m.d', 'Y-m-d\TH:i:s.v\Z', 'Y-m-d H:i:s', 'Y-m-d'] as $fallback) {
                try {
                    $parsed = Carbon::createFromFormat($fallback, $date);
                    break;
                } catch (\Throwable) {
                }
            }

            if (! $parsed) {
                throw new InvalidFormatException('Could not parse date: ' . $date);
            }
        }

        return $parsed->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }
}
