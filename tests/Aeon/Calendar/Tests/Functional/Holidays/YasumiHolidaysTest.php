<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Functional\Holidays;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\Gregorian\YasumiHolidays;
use PHPUnit\Framework\TestCase;

final class YasumiHolidaysTest extends TestCase
{
    public function test_yasumi_holidays() : void
    {
        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01'));
        $holidays = new YasumiHolidays('PL');

        $this->assertTrue($holidays->isHoliday(Day::fromString('2020-01-01')));
        $this->assertCount(1, $holidays->holidaysAt(Day::fromString('2020-01-01')));
    }

    public function test_yasumi_holidays_for_non_existing_provider() : void
    {
        $this->expectExceptionMessage('Country code NON_PROVIDER is ont assigned to any Yasumi provider');

        new YasumiHolidays('non_provider');
    }
}
