<?php

declare(strict_types=1);

namespace Aeon\Retry;

use Aeon\Calendar\System\SystemProcess;
use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier\ConstantDelay;

/**
 * @template FunctionReturnType
 *
 * @param callable(Execution $execution) : FunctionReturnType $function
 *
 * @throws \Throwable
 *
 * @return ?FunctionReturnType
 */
function retry(callable $function, int $retries, TimeUnit $delay, DelayModifier $delayModifier = null)
{
    return (new Retry(
        SystemProcess::current(),
        $retries,
        $delay
    ))->modifyDelay(
        $delayModifier === null
            ? new ConstantDelay()
            : $delayModifier
    )->execute($function);
}

/**
 * @template FunctionReturnType
 *
 * @param callable(Execution $execution) : FunctionReturnType $function
 * @param array<string> $exceptionClasses
 *
 * @throws \Throwable
 *
 * @return ?FunctionReturnType
 */
function retryOnlyFor(callable $function, int $retries, TimeUnit $delay, array $exceptionClasses, DelayModifier $delayModifier = null)
{
    return (new Retry(
        SystemProcess::current(),
        $retries,
        $delay
    ))->onlyFor(...$exceptionClasses)
    ->modifyDelay(
        $delayModifier === null
            ? new ConstantDelay()
            : $delayModifier
    )->execute($function);
}
