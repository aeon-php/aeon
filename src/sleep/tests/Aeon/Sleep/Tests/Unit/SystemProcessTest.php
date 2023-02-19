<?php

declare(strict_types=1);

namespace Aeon\Sleep\Tests\Unit;

use Aeon\Calendar\TimeUnit;
use Aeon\Sleep\SystemProcess;
use PHPUnit\Framework\TestCase;

final class SystemProcessTest extends TestCase
{
    public function test_sleeping_negative_time_unit() : void
    {
        $this->expectExceptionMessage("Sleep time unit can't be negative, -1.000000 given");

        SystemProcess::current()->sleep(TimeUnit::seconds(1)->invert());
    }
}
