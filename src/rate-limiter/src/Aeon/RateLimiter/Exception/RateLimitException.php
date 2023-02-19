<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Exception;

use Aeon\Calendar\TimeUnit;

final class RateLimitException extends RuntimeException
{
    private string $id;

    private int $limit;

    private TimeUnit $retryIn;

    private TimeUnit $reset;

    public function __construct(string $id, int $limit, TimeUnit $retryIn, TimeUnit $reset, \Throwable $previous = null)
    {
        parent::__construct("Execution \"{$id}\" was limited for the next " . $retryIn->inSecondsPrecise() . ' seconds', 0, $previous);

        $this->id = $id;
        $this->limit = $limit;
        $this->retryIn = $retryIn;
        $this->reset = $reset;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function limit() : int
    {
        return $this->limit;
    }

    public function retryIn() : TimeUnit
    {
        return $this->retryIn;
    }

    public function reset() : TimeUnit
    {
        return $this->reset;
    }
}
