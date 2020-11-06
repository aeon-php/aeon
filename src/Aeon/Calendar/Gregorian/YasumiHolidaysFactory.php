<?php

declare(strict_types=1);

namespace Aeon\Calendar\Gregorian;

final class YasumiHolidaysFactory implements HolidaysFactory
{
    public function create(string $countryCode) : Holidays
    {
        return new YasumiHolidays($countryCode);
    }
}
