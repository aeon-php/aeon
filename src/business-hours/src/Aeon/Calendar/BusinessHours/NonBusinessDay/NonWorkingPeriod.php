<?php

declare(strict_types=1);

namespace Aeon\Calendar\BusinessHours\NonBusinessDay;

use Aeon\Calendar\BusinessHours\NonBusinessDay;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\TimePeriod;
use Aeon\Calendar\TimeUnit;

/**
 * @psalm-immutable
 */
final class NonWorkingPeriod implements NonBusinessDay
{
    private TimePeriod $timePeriod;

    public function __construct(TimePeriod $timePeriod)
    {
        $this->timePeriod = $timePeriod;
    }

    /**
     * @psalm-suppress ImpureMethodCall
     */
    public function is(Day $day) : bool
    {
        $days = $this->timePeriod
            ->iterate(TimeUnit::day(), Interval::closed())
            ->filter(function (TimePeriod $timePeriod) use ($day) : bool {
                return $timePeriod->start()->day()->isEqualTo($day);
            });

        return (bool) $days->count();
    }
}
