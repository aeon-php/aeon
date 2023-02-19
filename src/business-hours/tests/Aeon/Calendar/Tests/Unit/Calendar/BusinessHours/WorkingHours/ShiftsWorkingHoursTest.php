<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Unit\Calendar\BusinessHours\WorkingHours;

use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\BusinessHours\WorkingHours\ShiftsWorkingHours;
use Aeon\Calendar\Gregorian\Time;
use PHPUnit\Framework\TestCase;

final class ShiftsWorkingHoursTest extends TestCase
{
    public function test_opening_and_closing_hours() : void
    {
        $shifts = new ShiftsWorkingHours(
            new LinearWorkingHours(Time::fromString('10am'), Time::fromString('9pm')),
            new LinearWorkingHours(Time::fromString('6am'), Time::fromString('8am')),
        );

        $this->assertSame(6, $shifts->openFrom()->hour());
        $this->assertSame(21, $shifts->openTo()->hour());
    }

    public function test_is_open() : void
    {
        $shifts = new ShiftsWorkingHours(
            new LinearWorkingHours(Time::fromString('10am'), Time::fromString('9pm')),
            new LinearWorkingHours(Time::fromString('6am'), Time::fromString('8am')),
            new LinearWorkingHours(Time::fromString('4am'), Time::fromString('5am')),
        );

        $this->assertSame('04:00:00.000000', $shifts->openFrom()->toString());
        $this->assertSame('21:00:00.000000', $shifts->openTo()->toString());
        $this->assertFalse($shifts->isOpen(Time::fromString('3am')));
        $this->assertTrue($shifts->isOpen(Time::fromString('6am')));
        $this->assertFalse($shifts->isOpen(Time::fromString('9am')));
        $this->assertTrue($shifts->isOpen(Time::fromString('5pm')));
        $this->assertTrue($shifts->isOpen(Time::fromString('9pm')));
        $this->assertFalse($shifts->isOpen(Time::fromString('11pm')));
    }

    public function test_creating_empty_shifts() : void
    {
        $this->expectExceptionMessage('Shifts can\'t be empty');

        new ShiftsWorkingHours();
    }
}
