<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Functional\Holidays;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\YasumiHolidays;
use PHPUnit\Framework\TestCase;
use Yasumi\Provider\Poland;

final class YasumiHolidaysTest extends TestCase
{
    public function test_yasumi_holidays() : void
    {
        $holidays = YasumiHolidays::provider(Poland::class, new GregorianCalendarStub(new \DateTimeImmutable('2020-01-01')));

        $this->assertTrue($holidays->isHoliday(Day::fromString('2020-01-01')));
        $this->assertCount(1, $holidays->holidaysAt(Day::fromString('2020-01-01')));
    }

    public function test_yasumi_holidays_for_non_existig_provider() : void
    {
        $this->expectExceptionMessage('non_provider is not valid Yasumi provider class.');

        YasumiHolidays::provider('non_provider', new GregorianCalendarStub(new \DateTimeImmutable('2020-01-01')));
    }
}
