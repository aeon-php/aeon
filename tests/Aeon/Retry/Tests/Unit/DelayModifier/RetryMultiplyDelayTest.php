<?php

declare(strict_types=1);

namespace Aeon\Retry\Tests\Unit\DelayModifier;

use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier\RetryMultiplyDelay;
use PHPUnit\Framework\TestCase;

final class RetryMultiplyDelayTest extends TestCase
{
    public function test_delay_multiplication() : void
    {
        $delay = new RetryMultiplyDelay();

        $this->assertEquals($delay->modify(5, TimeUnit::seconds(10)), TimeUnit::seconds(50));
    }
}
