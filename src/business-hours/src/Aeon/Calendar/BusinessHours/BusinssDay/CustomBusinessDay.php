<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\BusinssDay;

use Aeon\Calendar\BusinessHours\BusinessDay;
use Aeon\Calendar\BusinessHours\WorkingHours;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;

/**
 * @psalm-immutable
 */
final class CustomBusinessDay implements BusinessDay
{
    private Day $day;

    private WorkingHours $workingHours;

    public function __construct(Day $day, WorkingHours $workingHours)
    {
        $this->day = $day;
        $this->workingHours = $workingHours;
    }

    public function is(Day $day) : bool
    {
        return $this->day->isEqualTo($day);
    }

    public function isOpen(DateTime $dateTime) : bool
    {
        return $this->is($dateTime->day()) && $this->workingHours()->isOpen($dateTime->time());
    }

    public function workingHours() : WorkingHours
    {
        return $this->workingHours;
    }
}
