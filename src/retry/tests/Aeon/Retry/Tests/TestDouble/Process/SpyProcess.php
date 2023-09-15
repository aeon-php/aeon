<?php

namespace Aeon\Retry\Tests\TestDouble\Process;

use Aeon\Calendar\TimeUnit;
use Aeon\Sleep\Process;
use Aeon\Sleep\SystemProcess;

final class SpyProcess implements Process
{
    public array $calls = [];

    public function sleep(TimeUnit $timeUnit) : void
    {
        $this->calls[] = $timeUnit;
    }
}
