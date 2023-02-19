<?php

declare(strict_types=1);

namespace Aeon\Retry;

use Aeon\Calendar\TimeUnit;

interface DelayModifier
{
    public function modify(int $retry, TimeUnit $timeUnit) : TimeUnit;
}
