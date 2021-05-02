<?php

declare(strict_types=1);

namespace Aeon\Twig\Tests\Unit;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\TimeUnit;
use Aeon\Twig\CalendarExtension;
use PHPUnit\Framework\TestCase;

final class CalendarExtensionTest extends TestCase
{
    public function test_create_with_invalid_timezone() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not_timezone is not valid timezone name.');

        new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()), 'not_timezone');
    }

    public function test_aeon_now() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now, $extension->aeon_now());
        $this->assertEquals(TimeZone::europeWarsaw(), $extension->aeon_now('Europe/Warsaw')->timeZone());
    }

    public function test_aeon_datetime_format_takes_timezone_from_calendar_instance_when_not_provided() : void
    {
        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 UTC'));

        $extension = new CalendarExtension($calendar, 'Europe/Warsaw');

        $this->assertEquals(
            '2020-01-01 01:00:00',
            $extension->aeon_datetime_format(DateTime::fromString('2020-01-01 00:00:00 UTC'))
        );
    }

    public function test_aeon_datetime_format_takes_timezone_from_argument_when_provided() : void
    {
        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 Europe/Warsaw'));

        $extension = new CalendarExtension($calendar);

        $this->assertEquals(
            '2019-12-31 16:00:00',
            $extension->aeon_datetime_format(DateTime::fromString('2020-01-01 00:00:00 UTC'), null, 'America/Los_Angeles')
        );
    }

    public function test_aeon_in_seconds_precise() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals('10.000000', $extension->aeon_in_seconds_precise(TimeUnit::seconds(10)));
    }

    public function test_aeon_interval() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(Interval::closed(), $extension->aeon_interval('closed'));
        $this->assertEquals($extension->aeon_interval('CLOSED'), $extension->aeon_interval('closed'));
    }

    public function test_aeon_interval_left_open() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(Interval::leftOpen(), $extension->aeon_interval_left_open());
    }

    public function test_aeon_interval_right_open() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(Interval::rightOpen(), $extension->aeon_interval_right_open());
    }

    public function test_aeon_interval_open() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(Interval::open(), $extension->aeon_interval_open());
    }

    public function test_aeon_interval_closed() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(Interval::closed(), $extension->aeon_interval_closed());
    }

    public function test_aeon_in_seconds() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(10, $extension->aeon_in_seconds(TimeUnit::seconds(10)));
    }

    public function test_aeon_second() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(TimeUnit::seconds(15), $extension->aeon_second(15));
    }

    public function test_aeon_minute() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(TimeUnit::minutes(15), $extension->aeon_minute(15));
    }

    public function test_aeon_hour() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(TimeUnit::hours(15), $extension->aeon_hour(15));
    }

    public function test_aeon_day() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertEquals(TimeUnit::days(15), $extension->aeon_day(15));
    }

    public function test_aeon_date_format() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertSame('2020-01-01 00', $extension->aeon_datetime_format(DateTime::fromString('2020-01-01 00:00:00 UTC'), 'Y-m-d H'));
    }

    public function test_aeon_day_format() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertSame('2020 01 01', $extension->aeon_day_format(Day::fromString('2020-01-01'), 'Y m d'));
    }

    public function test_aeon_current_day() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->day(), $extension->aeon_current_day());
    }

    public function test_aeon_current_month() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->month(), $extension->aeon_current_month());
    }

    public function test_aeon_current_year() : void
    {
        $extension = new CalendarExtension($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow($now = DateTime::fromString('2002-01-01 00:00:00 UTC'));

        $this->assertEquals($now->year(), $extension->aeon_current_year());
    }

    public function test_aeon_interval_throws_exception_when_invalid_type() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid interval type: invalid interval');

        $extension = new CalendarExtension(new GregorianCalendarStub(TimeZone::UTC()));
        $extension->aeon_interval('invalid interval');
    }
}
