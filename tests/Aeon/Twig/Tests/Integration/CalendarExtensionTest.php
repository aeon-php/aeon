<?php

declare(strict_types=1);

namespace Aeon\Twig\Tests\Integration;

use Aeon\Calendar\Gregorian\GregorianCalendarStub;
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
        $this->calendarStub = new GregorianCalendarStub(new \DateTimeImmutable('2020-01-01 00:00:00 UTC'));
    }

    public function test_extension() : void
    {
        $this->assertInstanceOf(ExtensionInterface::class, new CalendarExtension($this->calendarStub));
    }

    public function test_filter_aeon_date() : void
    {
        $twig = new Environment(
            new FilesystemLoader(
                [
                    __DIR__ . '/Fixtures/filters',
                ]
            )
        );
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'Y-m-d H:i:sO', 'Europe/Warsaw'));

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/filters/aeon_date.txt',
            $twig->render('aeon_date.twig.txt')
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
        $twig->addExtension(new CalendarExtension($this->calendarStub, 'Y-m-d H:i:sO', 'Europe/Warsaw'));

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
