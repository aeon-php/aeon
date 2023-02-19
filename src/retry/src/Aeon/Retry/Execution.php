<?php

declare(strict_types=1);

namespace Aeon\Retry;

use Aeon\Calendar\Exception\InvalidArgumentException;

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
     * @psalm-suppress DocblockTypeContradiction
     */
    public function __construct(int $retry, array $exceptions = [])
    {
        foreach ($exceptions as $exception) {
            if (!$exception instanceof \Throwable) {
                throw new InvalidArgumentException("All exceptions must implements \Throwable interface");
            }
        }

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
