<?php

declare(strict_types=1);

namespace Aeon\Twig\Tests\Integration;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Twig\CalendarExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

final class CalendarExtensionTest extends TestCase
{
    private GregorianCalendarStub $calendarStub;

    public function setUp() : void
    {
        $this->calendarStub = new GregorianCalendarStub(TimeZone::UTC());
        $this->calendarStub->setNow(DateTime::fromString('2020-01-01 00:00:00'));
    }

    public function test_extension() : void
    {
        $this->assertInstanceOf(ExtensionInterface::class, new CalendarExtension($this->calendarStub));
    }

    public function test_filter_aeon_datetime() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'UTC', 'Y-m-d H:i:sO', 'Y-m-d', 'H:i:s'));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_datetime_format.txt',
            $twig->render('aeon_datetime_format.twig.txt')
        );
    }

    public function test_filter_aeon_day() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'UTC', 'Y-m-d H:i:sO', 'Y-m-d', 'H:i:s'));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_day_format.txt',
            $twig->render('aeon_day_format.twig.txt')
        );
    }

    public function test_filter_aeon_time() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'UTC', 'Y-m-d H:i:sO', 'Y-m-d', 'H:i:s'));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_time_format.txt',
            $twig->render('aeon_time_format.twig.txt')
        );
    }

    public function test_filter_time_units() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_time_units.txt',
            $twig->render('aeon_time_units.twig.txt')
        );
    }

    public function test_filter_interval() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_interval.txt',
            $twig->render('aeon_interval.twig.txt')
        );
    }

    public function test_function_aeon_now() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/functions',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'UTC', 'Y-m-d H:i:sO', 'Y-m-d'));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/functions/aeon_now.txt',
            $twig->render('aeon_now.twig.txt')
        );
    }

    public function test_function_aeon_current_day() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/functions',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/functions/aeon_current_day.txt',
            $twig->render('aeon_current_day.twig.txt')
        );
    }

    public function test_function_aeon_current_month() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/functions',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/functions/aeon_current_month.txt',
            $twig->render('aeon_current_month.twig.txt')
        );
    }

    public function test_function_aeon_current_year() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/functions',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/functions/aeon_current_year.txt',
            $twig->render('aeon_current_year.twig.txt')
        );
    }
}
