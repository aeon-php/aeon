<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Unit\Calendar\BusinessHours\BusinessHours\BusinessDay;

use Aeon\Calendar\BusinessHours\BusinssDay\CustomBusinessDay;
use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Time;
use PHPUnit\Framework\TestCase;

final class CustomBusinessDayTest extends TestCase
{
    public function test_business_hours_coverage() : void
    {
        $customBusinessDay = new CustomBusinessDay(
            Day::fromString('2020-01-01'),
            new LinearWorkingHours(Time::fromString('8am'), Time::fromString('6pm'))
        );

        $this->assertTrue($customBusinessDay->isOpen(DateTime::fromString('2020-01-01 10am')));
        $this->assertFalse($customBusinessDay->isOpen(DateTime::fromString('2020-01-01 11pm')));
    }
}
