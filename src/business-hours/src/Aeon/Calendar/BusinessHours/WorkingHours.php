<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours;

use Aeon\Calendar\Gregorian\Time;

/**
 * @psalm-immutable
 */
interface WorkingHours
{
    public function openFrom() : Time;

    public function openTo() : Time;

    public function isOpen(Time $time) : bool;
}
