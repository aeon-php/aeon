<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours;

use Aeon\Calendar\BusinessHours\Exception\BusinessDayException;
use Aeon\Calendar\BusinessHours\WorkingHours\LinearWorkingHours;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;

/**
 * @psalm-immutable
 */
final class BusinessDays
{
    /**
     * @var array<BusinessDay>
     */
    private array $businessDays;

    public function __construct(BusinessDay ...$businessDays)
    {
        $this->businessDays = $businessDays;
    }

    /**
     * @psalm-pure
     */
    public static function none() : self
    {
        return new self();
    }

    /**
     * @psalm-pure
     */
    public static function wholeWeek(WorkingHours $weekWorkingHours, ?WorkingHours $weekendWorkingHours = null) : self
    {
        return new self(
            new RegularBusinessDay(Day\WeekDay::monday(), $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::tuesday(), $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::wednesday(), $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::thursday(), $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::friday(), $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::saturday(), $weekendWorkingHours ? $weekendWorkingHours : $weekWorkingHours),
            new RegularBusinessDay(Day\WeekDay::sunday(), $weekendWorkingHours ? $weekendWorkingHours : $weekWorkingHours),
        );
    }

    /**
     * @psalm-pure
     */
    public static function mondayFriday(LinearWorkingHours $workingHours) : self
    {
        return new self(
            new RegularBusinessDay(Day\WeekDay::monday(), $workingHours),
            new RegularBusinessDay(Day\WeekDay::tuesday(), $workingHours),
            new RegularBusinessDay(Day\WeekDay::wednesday(), $workingHours),
            new RegularBusinessDay(Day\WeekDay::thursday(), $workingHours),
            new RegularBusinessDay(Day\WeekDay::friday(), $workingHours),
        );
    }

    public function isOpen(DateTime $dateTime) : bool
    {
        foreach ($this->businessDays as $customBusinessDay) {
            if ($customBusinessDay->isOpen($dateTime)) {
                return true;
            }
        }

        return false;
    }

    public function isOpenOn(Day $day) : bool
    {
        try {
            /**
             * @psalm-suppress UnusedMethodCall
             */
            $this->get($day);

            return true;
        } catch (BusinessDayException $businessDayException) {
            return false;
        }
    }

    public function get(Day $day) : BusinessDay
    {
        foreach ($this->businessDays as $regularBusinessDay) {
            if ($regularBusinessDay->is($day)) {
                return $regularBusinessDay;
            }
        }

        throw new BusinessDayException($day->format('Y-m-d') . ' is not a business day.');
    }
}
