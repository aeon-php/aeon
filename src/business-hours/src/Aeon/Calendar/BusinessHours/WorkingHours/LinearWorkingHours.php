<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\WorkingHours;

use Aeon\Calendar\BusinessHours\WorkingHours;
use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\Time;

/**
 * @psalm-immutable
 */
final class LinearWorkingHours implements WorkingHours
{
    private Time $startHour;

    private Time $endHour;

    public function __construct(Time $startHour, Time $endHour)
    {
        if (!$endHour->isAfter($startHour)) {
            throw new InvalidArgumentException('End hour needs to be greater than start hour');
        }

        $this->startHour = $startHour;
        $this->endHour = $endHour;
    }

    public function openFrom() : Time
    {
        return $this->startHour;
    }

    public function openTo() : Time
    {
        return $this->endHour;
    }

    public function isOpen(Time $time) : bool
    {
        return $time->isAfterOrEqualTo($this->openFrom()) &&
            $time->isBeforeOrEqualTo($this->openTo());
    }
}
