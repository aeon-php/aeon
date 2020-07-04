<?php

declare(strict_types=1);

namespace Aeon\Retry\DelayModifier;

use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier;

final class RetryMultiplyDelay implements DelayModifier
{
    public function modify(int $retry, TimeUnit $timeUnit) : TimeUnit
    {
        return $timeUnit->multiply($retry);
    }
}
