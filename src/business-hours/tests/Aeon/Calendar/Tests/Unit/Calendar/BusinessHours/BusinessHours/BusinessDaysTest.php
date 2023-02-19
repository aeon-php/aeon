<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Unit\Calendar\BusinessHours\BusinessHours;

use Aeon\Calendar\BusinessHours\BusinessDays;
use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Time;
use PHPUnit\Framework\TestCase;

final class BusinessDaysTest extends TestCase
{
    public function test_whole_week_business_days() : void
    {
        $businessDays = BusinessDays::wholeWeek(
            new LinearWorkingHours(Time::fromString('8am'), Time::fromString('6pm'))
        );

        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-06 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-07 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-08 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-09 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-10 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-11 10am')));
        $this->assertTrue($businessDays->isOpen(DateTime::fromString('2020-01-12 10am')));
    }
}
