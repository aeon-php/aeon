<?php

declare(strict_types=1);

namespace Aeon\Retry\Tests\Unit;

use Aeon\Calendar\System\DummyProcess;
use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier\ConstantDelay;
use Aeon\Retry\Exception\RetryException;
use Aeon\Retry\Execution;
use Aeon\Retry\Retry;
use PHPUnit\Framework\TestCase;

final class RetryTest extends TestCase
{
    public function test_retry_return_function_value() : void
    {
        $retry = new Retry(
            new DummyProcess(),
            3,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) : int {
            return 1;
        }));
    }

    public function test_retry_return_function_value_for_0_retries() : void
    {
        $retry = new Retry(
            new DummyProcess(),
            0,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) : int {
            return 1;
        }));
    }

    public function test_retry_that_not_reached_all_retries_but_handled_exception() : void
    {
        $retry = new Retry(
            new DummyProcess(),
            3,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) : int {
            if ($execution->retry() === 0) {
                throw new \RuntimeException('Exception');
            }

            return 1;
        }));
        $this->assertCount(1, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
    }

    public function test_retry_when_reached_all_exceptions() : void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Exception');

        $retry = new Retry(
            new DummyProcess(),
            3,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            throw new \RuntimeException('Exception');
        }));

        $this->assertCount(3, $retry->lastExecution()->exceptions());
        $this->assertSame(3, $retry->lastExecution()->retry());
    }

    public function test_retry_exception_when_0_retries() : void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Exception');

        $retry = new Retry(
            new DummyProcess(),
            0,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            throw new \RuntimeException('Exception');
        }));

        $this->assertCount(1, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
    }

    public function test_continue_during_retries() : void
    {
        $retry = new Retry(
            new DummyProcess(),
            5,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            if ($execution->retry() === 0) {
                $execution->continue();
            }

            return 1;
        }));

        $this->assertCount(0, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
    }

    public function test_continue_all_attempts() : void
    {
        $this->expectException(RetryException::class);
        $this->expectExceptionMessage('Retry failed to execute function and return value in 5 attempts');

        $retry = new Retry(
            new DummyProcess(),
            5,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            $execution->continue();
        }));

        $this->assertCount(0, $retry->lastExecution()->exceptions());
        $this->assertSame(5, $retry->lastExecution()->retry());
    }

    public function test_termination_execution() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Terminated');

        $retry = new Retry(
            new DummyProcess(),
            5,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            $execution->terminate(new \Exception("Terminated"));
        }));

        $this->assertCount(1, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
        $this->assertTrue($retry->lastExecution()->isTerminated());
    }

    public function test_termination_execution_for_0_retries() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Terminated');

        $retry = new Retry(
            new DummyProcess(),
            0,
            TimeUnit::seconds(3)
        );

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            $execution->terminate(new \Exception("Terminated"));
        }));

        $this->assertCount(1, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
    }

    public function test_breaking_execution_when_exception_not_in_exceptions_list() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Break retries');

        $retry = (new Retry(
            new DummyProcess(),
            5,
            TimeUnit::seconds(3)
        ))->onlyFor(\RuntimeException::class);

        $this->assertSame(1, $retry->execute(function (Execution $execution) {
            $execution->terminate(new \Exception("Break retries"));
        }));

        $this->assertCount(1, $retry->lastExecution()->exceptions());
        $this->assertSame(1, $retry->lastExecution()->retry());
    }

    public function test_last_execution_when_not_executed() : void
    {
        $this->expectException(RetryException::class);
        $this->expectExceptionMessage('Never executed');

        $retry = (new Retry(
            new DummyProcess(),
            5,
            TimeUnit::seconds(3)
        ))->modifyDelay(new ConstantDelay())
            ->lastExecution();
    }
}
