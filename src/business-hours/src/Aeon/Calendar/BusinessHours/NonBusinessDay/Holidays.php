<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\NonBusinessDay;

use Aeon\Calendar\BusinessHours\NonBusinessDay;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Holidays as CalendarHolidays;

/**
 * @psalm-immutable
 */
final class Holidays implements NonBusinessDay
{
    private CalendarHolidays $holidays;

    public function __construct(CalendarHolidays $holidays)
    {
        $this->holidays = $holidays;
    }

    public function is(Day $day) : bool
    {
        return $this->holidays->isHoliday($day);
    }
}
