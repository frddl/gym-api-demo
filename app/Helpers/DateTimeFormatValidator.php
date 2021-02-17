<?php


namespace App\Helpers;


use Carbon\Carbon;

class DateTimeFormatValidator
{
    public static function dateIsValid(string $date): bool {
        $cdate = Carbon::parse($date);
        return (!$cdate || $cdate < Carbon::now());
    }

    public static function timeDurationIsValid(string $start, string $end): bool {
        $regex = "/^\d{2}:\d{2}$/";

        $startValid = preg_match($regex, $start);
        $endValid = preg_match($regex, $start);

        $durationValid = Carbon::parse($start)->lessThan(Carbon::parse($end));

        return $startValid && $endValid && $durationValid;
    }
}
