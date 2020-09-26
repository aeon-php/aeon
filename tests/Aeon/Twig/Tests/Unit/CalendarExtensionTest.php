<?php

declare(strict_types=1);

namespace Aeon\Twig\Tests\Unit;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Twig\CalendarExtension;
use PHPUnit\Framework\TestCase;

final class CalendarExtensionTest extends TestCase
{
    public function test_aeon_now() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub());

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now, $extension->aeon_now());
        $this->assertEquals(TimeZone::europeWarsaw(), $extension->aeon_now(TimeZone::EUROPE_WARSAW)->timeZone());
    }

    public function test_aeon_date() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub());

        $this->assertSame('2020-01-01 00', $extension->aeon_date(DateTime::fromString('2020-01-01 00:00:00 UTC'), 'Y-m-d H'));
    }

    public function test_aeon_current_day() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub());

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->day(), $extension->aeon_current_day());
    }

    public function test_aeon_current_month() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub());

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->month(), $extension->aeon_current_month());
    }

    public function test_aeon_current_year() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub());

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->year(), $extension->aeon_current_year());
    }
}
