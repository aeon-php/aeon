<?php

declare(strict_types=1);

namespace Aeon\Retry;

use Webmozart\Assert\Assert;

final class Execution
{
    private int $retry;

    private bool $continue;

    private ?\Throwable $terminationException;

    /**
     * @var array<\Throwable>
     */
    private array $exceptions;

    /**
     * @param int $retry
     * @param array<\Throwable> $exceptions
     */
    public function __construct(int $retry, array $exceptions = [])
    {
        Assert::allIsInstanceOf($exceptions, \Throwable::class);

        $this->retry = $retry;
        $this->continue = false;
        $this->terminationException = null;
        $this->exceptions = $exceptions;
    }

    public function retry() : int
    {
        return $this->retry;
    }

    public function terminate(\Throwable $terminationException) : void
    {
        $this->terminationException = $terminationException;
    }

    public function continue() : void
    {
        $this->continue = true;
    }

    public function isContinued() : bool
    {
        return $this->continue;
    }

    public function isTerminated() : bool
    {
        return $this->terminationException !== null;
    }

    public function terminationException() : ?\Throwable
    {
        return $this->terminationException;
    }

    /**
     * @return array<\Throwable>
     */
    public function exceptions() : array
    {
        return $this->exceptions;
    }

    public function lasException() : ?\Throwable
    {
        return \count($this->exceptions) ? \end($this->exceptions) : null;
    }
}
