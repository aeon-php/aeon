<?php

declare(strict_types=1);

namespace Aeon\Calendar\Holidays;

use Aeon\Calendar\Holidays;
use Aeon\Calendar\HolidaysFactory;

final class YasumiHolidaysFactory implements HolidaysFactory
{
    public function create(string $countryCode) : Holidays
    {
        return new YasumiHolidays($countryCode);
    }
}
