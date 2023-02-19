<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\WorkingHours;

use Aeon\Calendar\BusinessHours\WorkingHours;
use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\Time;

/**
 * @psalm-immutable
 */
final class ShiftsWorkingHours implements WorkingHours
{
    /**
     * @var array<int, LinearWorkingHours>
     */
    private array $workingHours;

    public function __construct(LinearWorkingHours ...$workingHours)
    {
        if (\count($workingHours) === 0) {
            throw new InvalidArgumentException('Shifts can\'t be empty');
        }

        \uasort(
            $workingHours,
            function (LinearWorkingHours $workingHoursA, LinearWorkingHours $workingHoursB) : int {
                return $workingHoursA->openFrom()->isBeforeOrEqualTo($workingHoursB->openFrom())
                    ? -1
                    : 1;
            }
        );

        $this->workingHours = \array_values($workingHours);
    }

    public function openFrom() : Time
    {
        return $this->workingHours[0]->openFrom();
    }

    public function openTo() : Time
    {
        return $this->workingHours[\count($this->workingHours) - 1]->openTo();
    }

    public function isOpen(Time $time) : bool
    {
        foreach ($this->workingHours as $workingHours) {
            if ($workingHours->isOpen($time)) {
                return true;
            }
        }

        return false;
    }
}
