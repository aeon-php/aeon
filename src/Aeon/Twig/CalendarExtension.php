<?php

declare(strict_types=1);

namespace Aeon\Twig;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\Month;
use Aeon\Calendar\Gregorian\Time;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\Gregorian\Year;
use Aeon\Calendar\TimeUnit;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CalendarExtension extends AbstractExtension
{
    private Calendar $calendar;

    private TimeZone $defaultTimeZone;

    private string $defaultDateTimeFormat;

    private string $defaultDayFormat;

    private string $defaultTimeFormat;

    public function __construct(Calendar $calendar, string $defaultTimeZone = 'UTC', string $defaultDateTimeFormat = 'Y-m-d H:i:s', string $defaultDayFormat = 'Y-m-d', string $defaultTimeFormat = 'H:i:s')
    {
        if (!TimeZone::isValid($defaultTimeZone)) {
            throw new InvalidArgumentException($defaultTimeZone . ' is not valid timezone name.');
        }

        $this->calendar = $calendar;
        $this->defaultTimeZone = TimeZone::fromString($defaultTimeZone);
        $this->defaultDateTimeFormat = $defaultDateTimeFormat;
        $this->defaultDayFormat = $defaultDayFormat;
        $this->defaultTimeFormat = $defaultTimeFormat;
    }

    public function getFilters() : array
    {
        return [
            new TwigFilter('aeon_datetime_format', [$this, 'aeon_datetime_format']),
            new TwigFilter('aeon_day_format', [$this, 'aeon_day_format']),
            new TwigFilter('aeon_time_format', [$this, 'aeon_time_format']),
            new TwigFilter('aeon_in_seconds', [$this, 'aeon_in_seconds']),
            new TwigFilter('aeon_in_seconds_precise', [$this, 'aeon_in_seconds_precise']),
            new TwigFilter('aeon_interval', [$this, 'aeon_interval']),
            new TwigFilter('aeon_second', [$this, 'aeon_second']),
            new TwigFilter('aeon_seconds', [$this, 'aeon_second']),
            new TwigFilter('aeon_minute', [$this, 'aeon_minute']),
            new TwigFilter('aeon_minutes', [$this, 'aeon_minute']),
            new TwigFilter('aeon_hour', [$this, 'aeon_hour']),
            new TwigFilter('aeon_hours', [$this, 'aeon_hour']),
            new TwigFilter('aeon_day', [$this, 'aeon_day']),
            new TwigFilter('aeon_days', [$this, 'aeon_day']),
        ];
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('aeon_now', [$this, 'aeon_now']),
            new TwigFunction('aeon_current_time', [$this, 'aeon_current_time']),
            new TwigFunction('aeon_current_day', [$this, 'aeon_current_day']),
            new TwigFunction('aeon_current_month', [$this, 'aeon_current_month']),
            new TwigFunction('aeon_current_year', [$this, 'aeon_current_year']),
            new TwigFunction('aeon_interval_closed', [$this, 'aeon_interval_closed']),
            new TwigFunction('aeon_interval_open', [$this, 'aeon_interval_open']),
            new TwigFunction('aeon_interval_right_open', [$this, 'aeon_interval_right_open']),
            new TwigFunction('aeon_interval_left_open', [$this, 'aeon_interval_left_open']),
        ];
    }

    public function aeon_datetime_format(DateTime $dateTime, string $format = null, string $timezone = null) : string
    {
        $tz = (\is_string($timezone) && TimeZone::isValid($timezone))
            ? TimeZone::fromString($timezone)
            : null;

        $fmt = \is_string($format)
            ? $format
            : $this->defaultDateTimeFormat;

        if ($tz instanceof TimeZone) {
            return $dateTime->toTimeZone($tz)->format($fmt);
        }

        return $dateTime->toTimeZone($this->defaultTimeZone)->format($fmt);
    }

    public function aeon_time_format(Time $time, string $format = null) : string
    {
        $fmt = \is_string($format)
            ? $format
            : $this->defaultTimeFormat;

        return $time->format($fmt);
    }

    public function aeon_day_format(Day $day, string $format = null) : string
    {
        $fmt = \is_string($format)
            ? $format
            : $this->defaultDayFormat;

        return $day->format($fmt);
    }

    public function aeon_interval(string $interval) : Interval
    {
        switch (\strtolower($interval)) {
            case 'open':
                return Interval::open();
            case 'closed':
                return Interval::closed();
            case 'right_open':
            case 'right open':
                return Interval::rightOpen();
            case 'left_open':
            case 'left open':
                return Interval::leftOpen();

            default:
                throw new InvalidArgumentException('Invalid interval type: ' . $interval);
        }
    }

    public function aeon_in_seconds_precise(TimeUnit $timeUnit) : string
    {
        return $timeUnit->inSecondsPrecise();
    }

    public function aeon_in_seconds(TimeUnit $timeUnit) : int
    {
        return $timeUnit->inSeconds();
    }

    public function aeon_second(int $seconds) : TimeUnit
    {
        return TimeUnit::seconds($seconds);
    }

    public function aeon_minute(int $minutes) : TimeUnit
    {
        return TimeUnit::minutes($minutes);
    }

    public function aeon_hour(int $hours) : TimeUnit
    {
        return TimeUnit::hours($hours);
    }

    public function aeon_day(int $days) : TimeUnit
    {
        return TimeUnit::days($days);
    }

    public function aeon_now(string $timezone = null) : DateTime
    {
        if (\is_string($timezone) && TimeZone::isValid($timezone)) {
            return $this->calendar->now()->toTimeZone(TimeZone::fromString($timezone));
        }

        return $this->calendar->now();
    }

    public function aeon_current_time() : Time
    {
        return $this->calendar->now()->time();
    }

    public function aeon_current_day() : Day
    {
        return $this->calendar->currentDay();
    }

    public function aeon_current_month() : Month
    {
        return $this->calendar->currentMonth();
    }

    public function aeon_current_year() : Year
    {
        return $this->calendar->currentYear();
    }

    public function aeon_interval_left_open() : Interval
    {
        return Interval::leftOpen();
    }

    public function aeon_interval_right_open() : Interval
    {
        return Interval::rightOpen();
    }

    public function aeon_interval_closed() : Interval
    {
        return Interval::closed();
    }

    public function aeon_interval_open() : Interval
    {
        return Interval::open();
    }
}
