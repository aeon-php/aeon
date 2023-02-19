<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours;

use Aeon\Calendar\Gregorian\Day;

/**
 * @psalm-immutable
 */
interface NonBusinessDay
{
    public function is(Day $day) : bool;
}
