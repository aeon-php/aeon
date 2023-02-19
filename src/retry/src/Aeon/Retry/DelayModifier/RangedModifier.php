<?php

declare(strict_types=1);

namespace Aeon\Retry\DelayModifier;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier;

final class RangedModifier implements DelayModifier
{
    private TimeUnit $rangeStart;

    private TimeUnit $rangeEnd;

    private function __construct(TimeUnit $rangeStart, TimeUnit $rangeEnd)
    {
        if ($rangeEnd->inSecondsPrecise() <= $rangeStart->inSecondsPrecise()) {
            throw new InvalidArgumentException('Range end must be greater than range start');
        }

        $this->rangeStart = $rangeStart;
        $this->rangeEnd = $rangeEnd;
    }

    public static function between(TimeUnit $rangeStart, TimeUnit $rangeEnd) : self
    {
        return new self($rangeStart, $rangeEnd);
    }

    public function modify(int $retry, TimeUnit $timeUnit) : TimeUnit
    {
        if ($this->rangeStart->isGreaterThan(TimeUnit::second())) {
            return $timeUnit->add(TimeUnit::seconds(
                \random_int($this->rangeStart->inSeconds(), $this->rangeEnd->inSeconds())
            ));
        }

        return $timeUnit->add(TimeUnit::milliseconds(
            \random_int($this->rangeStart->inMilliseconds(), $this->rangeEnd->inMilliseconds())
        ));
    }
}
