<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Unit\Calendar\BusinessHours\BusinessHours;

use Aeon\Calendar\BusinessHours\RegularBusinessDay;
use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day\WeekDay;
use Aeon\Calendar\Gregorian\Time;
use PHPUnit\Framework\TestCase;

final class RegularBusinessDayTest extends TestCase
{
    public function test_is_open() : void
    {
        $businessDay = new RegularBusinessDay(WeekDay::friday(), new LinearWorkingHours(Time::fromString('5am'), Time::fromString('5pm')));

        $this->assertTrue($businessDay->isOpen(DateTime::fromString('2020-01-10 10:00:00')));
        $this->assertFalse($businessDay->isOpen(DateTime::fromString('2020-01-11 10:00:00')));
    }
}
