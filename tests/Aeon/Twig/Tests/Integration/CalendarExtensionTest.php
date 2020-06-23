<?php

declare(strict_types=1);

namespace Aeon\Twig\Tests\Integration;

use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Twig\CalendarExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Test\IntegrationTestCase;

final class CalendarExtensionTest extends IntegrationTestCase
{
    private GregorianCalendarStub $calendarStub;

    public function setUp() : void
    {
        $this->calendarStub = new GregorianCalendarStub(new \DateTimeImmutable('2020-01-01 00:00:00 UTC'));
    }

    protected function getFixturesDir() : string
    {
        return __DIR__ . '/Fixtures';
    }

    protected function getExtensions() : array
    {
        return [new CalendarExtension($this->calendarStub, 'Y-m-d H:i:sO', 'Europe/Warsaw')];
    }

    public function test_extension() : void
    {
        $this->assertInstanceOf(ExtensionInterface::class, new CalendarExtension($this->calendarStub));
    }
}
