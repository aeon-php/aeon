<?php

declare(strict_types=1);

namespace Aeon\Twig;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimeZone;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CalendarExtension extends AbstractExtension
{
    private Calendar $calendar;

    private string $defaultFormat;

    private ?string $defaultTimeZone;

    public function __construct(Calendar $calendar, string $defaultFormat = 'Y-m-d H:i:s', string $defaultTimeZone = null)
    {
        if (\is_string($defaultTimeZone)) {
            if (!TimeZone::isValid($defaultTimeZone)) {
                throw new InvalidArgumentException($defaultTimeZone . ' is not valid timezone.');
            }
        }

        $this->calendar = $calendar;
        $this->defaultFormat = $defaultFormat;
        $this->defaultTimeZone = $defaultTimeZone;
    }

    public function getFilters() : array
    {
        return [
            new TwigFilter('aeon_date', [$this, 'aeon_date']),
        ];
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('aeon_now', [$this, 'aeon_now']),
        ];
    }

    public function aeon_date(DateTime $dateTime, string $format = null, string $timezone = null) : string
    {
        $tz = \is_string($timezone)
            ? $timezone
            : (\is_string($this->defaultTimeZone) ? $this->defaultTimeZone : null);

        $fmt = \is_string($format)
            ? $format
            : $this->defaultFormat;

        if (\is_string($tz)) {
            return $dateTime->toTimeZone(new TimeZone($tz))->format($fmt);
        }

        return $dateTime->format($fmt);
    }

    public function aeon_now(string $timezone = null) : DateTime
    {
        if (\is_string($timezone) && TimeZone::isValid($timezone)) {
            return $this->calendar->now()->toTimeZone(new TimeZone($timezone));
        }

        return $this->calendar->now();
    }
}
