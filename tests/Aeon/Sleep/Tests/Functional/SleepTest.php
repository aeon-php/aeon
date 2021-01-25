<?php

declare(strict_types=1);

namespace Aeon\Sleep\Tests\Functional;

use Aeon\Calendar\Stopwatch;
use Aeon\Calendar\TimeUnit;
use Aeon\Sleep\SystemProcess;
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
