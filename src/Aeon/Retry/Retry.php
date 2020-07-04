<?php

declare(strict_types=1);

namespace Aeon\Retry;

use Aeon\Calendar\System\Process;
use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier\ConstantDelay;
use Aeon\Retry\Exception\RetryException;
use Webmozart\Assert\Assert;

final class Retry
{
    private int $retries;

    private TimeUnit $delay;

    private Process $process;

    private ?Execution $lastExecution;

    private DelayModifier $delayModifier;

    /**
     * @var array<string>
     */
    private $onlyForExceptions;

    public function __construct(
        Process $process,
        int $retries,
        TimeUnit $delay
    ) {
        Assert::greaterThanEq($retries, 0, "Number of retries must be greater or equal 0.");
        Assert::true($delay->isPositive(), "Delay between retries must be positive time unit.");

        $this->retries = $retries;
        $this->delay = $delay;
        $this->process = $process;
        $this->lastExecution = null;
        $this->delayModifier = new ConstantDelay();
        $this->onlyForExceptions = [];
    }

    public function modifyDelay(DelayModifier $delayModifier) : self
    {
        $this->delayModifier = $delayModifier;

        return $this;
    }

    public function onlyFor(string ...$exceptionClass) : self
    {
        Assert::allClassExists($exceptionClass);

        $this->onlyForExceptions = $exceptionClass;

        return $this;
    }

    /**
     * @template FunctionReturnType
     * @param \Closure(Execution $execution) : FunctionReturnType $function
     * @return ?FunctionReturnType
     * @throws \Throwable
     */
    public function execute(callable $function)
    {
        /**
         * @var array<\Throwable> $exceptions
         */
        $exceptions = [];

        if ($this->retries === 0) {
            try {
                $lastReturn = $function($this->lastExecution = new Execution(0));

                $terminationException = $this->lastExecution->terminationException();

                if ($terminationException) {
                    throw $terminationException;
                }

                return $lastReturn;
            } catch (\Throwable $throwable) {
                $exceptions[] = $throwable;

                throw $throwable;
            }
        }

        for ($retry = 0; $retry < $this->retries; $retry++) {
            try {
                $lastReturn = $function($this->lastExecution = new Execution($retry, $exceptions));

                if ($this->lastExecution->isContinued()) {
                    $this->wait();

                    continue ;
                }

                $terminationException = $this->lastExecution->terminationException();

                if ($terminationException) {
                    throw $terminationException;
                }

                return $lastReturn;
            } catch (\Throwable $throwable) {
                $exceptions[] = $throwable;

                if (\count($this->onlyForExceptions) && !\in_array(\get_class($throwable), $this->onlyForExceptions, true)) {
                    throw $throwable;
                }
            }

            $this->wait();
        }

        if ($this->lastExecution) {
            $lastException = $this->lastExecution()->lasException();

            if ($lastException instanceof \Throwable) {
                throw $lastException;
            }
        }

        throw new RetryException(\sprintf("Retry failed to execute function and return value in %d attempts", $this->retries));
    }

    public function wait() : void
    {
        $this->process->sleep($this->delayModifier->modify($this->lastExecution()->retry(), $this->delay));
    }

    public function lastExecution() : Execution
    {
        if ($this->lastExecution === null) {
            throw new RetryException("Never executed");
        }

        return $this->lastExecution;
    }
}
