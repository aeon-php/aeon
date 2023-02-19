<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\NonBusinessDay;

use Aeon\Calendar\BusinessHours\NonBusinessDay;
use Aeon\Calendar\Gregorian\Day;

/**
 * @psalm-immutable
 */
final class NonWorkingDay implements NonBusinessDay
{
    private Day $day;

    public function __construct(Day $day)
    {
        $this->day = $day;
    }

    public function is(Day $day) : bool
    {
        return $this->day->isEqualTo($day);
    }
}
