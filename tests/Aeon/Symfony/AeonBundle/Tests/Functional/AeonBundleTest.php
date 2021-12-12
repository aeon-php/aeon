<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;

final class AeonBundleTest extends TestSuite
{
    public function test_bundle_in_dev_env() : void
    {
        $kernel = self::bootKernel(['environment' => 'dev']);

        $this->assertInstanceOf(Calendar::class, $kernel->getContainer()->get(Calendar::class));
        $this->assertInstanceOf(Calendar::class, $kernel->getContainer()->get('calendar'));
        $this->assertInstanceOf(GregorianCalendar::class, $kernel->getContainer()->get(Calendar::class));
        $this->assertInstanceOf(TimeZone::class, $kernel->getContainer()->get(TimeZone::class));
    }

    public function test_bundle_in_test_env() : void
    {
        $kernel = self::bootKernel(['environment' => 'test']);

        $this->assertInstanceOf(Calendar::class, $kernel->getContainer()->get(Calendar::class));
        $this->assertInstanceOf(Calendar::class, $kernel->getContainer()->get('calendar'));
        $this->assertInstanceOf(GregorianCalendarStub::class, $kernel->getContainer()->get('calendar'));
        $this->assertInstanceOf(GregorianCalendarStub::class, $kernel->getContainer()->get(Calendar::class));
        $this->assertInstanceOf(GregorianCalendarStub::class, $kernel->getContainer()->get(GregorianCalendarStub::class));
        $this->assertInstanceOf(TimeZone::class, $kernel->getContainer()->get(TimeZone::class));
    }
}
