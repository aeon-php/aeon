<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;

/**
 * @psalm-immutable
 */
interface BusinessDay
{
    public function is(Day $day) : bool;

    public function isOpen(DateTime $dateTime) : bool;

    public function workingHours() : WorkingHours;
}
