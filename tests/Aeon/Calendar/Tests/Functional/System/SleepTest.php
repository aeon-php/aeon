<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Functional\System;

use Aeon\Calendar\Stopwatch;
use Aeon\Calendar\System\SystemProcess;
use Aeon\Calendar\TimeUnit;
use PHPUnit\Framework\TestCase;

final class SleepTest extends TestCase
{
    public function test_sleep_seconds() : void
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start();
        SystemProcess::current()->sleep(TimeUnit::precise(2.5));
        $stopwatch->stop();

        $this->assertEqualsWithDelta(2, $stopwatch->totalElapsedTime()->inSecondsPrecise(), 1);
    }
}
