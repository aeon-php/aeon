<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Day\WeekDay;

/**
 * @psalm-immutable
 */
final class RegularBusinessDay implements BusinessDay
{
    private WeekDay $weekDay;

    private WorkingHours $workingHours;

    public function __construct(WeekDay $weekDay, WorkingHours $workingHours)
    {
        $this->weekDay = $weekDay;
        $this->workingHours = $workingHours;
    }

    public function is(Day $day) : bool
    {
        return $this->weekDay->isEqualTo($day->weekDay());
    }

    public function isOpen(DateTime $dateTime) : bool
    {
        return $this->is($dateTime->day()) && $this->workingHours->isOpen($dateTime->time());
    }

    public function workingHours() : WorkingHours
    {
        return $this->workingHours;
    }
}
