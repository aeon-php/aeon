<?php

declare(strict_types=1);

namespace Aeon\Sleep;

use Aeon\Calendar\Exception\Exception;
use Aeon\Calendar\TimeUnit;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
function sleep(TimeUnit $timeUnit) : void
{
    if ($timeUnit->isNegative()) {
        throw new Exception(\sprintf("Sleep time unit can't be negative, %s given", $timeUnit->inSecondsPrecise()));
    }

    if ($timeUnit->inSeconds()) {
        \sleep($timeUnit->inSeconds());
    }

    if ($timeUnit->microsecond()) {
        \usleep($timeUnit->microsecond());
    }
}
