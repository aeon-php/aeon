<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Unit\Calendar\BusinessHours\WorkingHours;

use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\Gregorian\Time;
use PHPUnit\Framework\TestCase;

final class LinearWorkingHoursTest extends TestCase
{
    public function test_creating_working_hours_with_ending_hours_before_starting_hours() : void
    {
        $this->expectExceptionMessage('End hour needs to be greater than start hour');

        new LinearWorkingHours(Time::fromString('8pm'), Time::fromString('7pm'));
    }
}
