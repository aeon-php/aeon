<?php

declare(strict_types=1);

namespace Aeon\RateLimiter;

use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\Sleep\Process;

final class RateLimiter
{
    private Algorithm $algorithm;

    private Storage $storage;

    public function __construct(Algorithm $algorithm, Storage $storage)
    {
        $this->algorithm = $algorithm;
        $this->storage = $storage;
    }

    /**
     * Record next hit, throws an extension where there are no available hits left according to the selected algorithm.
     *
     * @throws RateLimitException
     */
    public function hit(string $id) : void
    {
        $this->algorithm->hit($id, $this->storage);
    }

    /**
     * Estimate time required to the next hit. If current capacity is greater than 0, time will be 0.
     */
    public function estimate(string $id) : TimeUnit
    {
        return $this->algorithm->estimate($id, $this->storage);
    }

    /**
     * Returns current capacity according to the selected algorithm, when there are no available hits left, it will return 0.
     * Use RateLimiter::estimate method to find out when next hit will be possible.
     */
    public function capacity(string $id) : int
    {
        return $this->algorithm->capacity($id, $this->storage);
    }

    /**
     * Initial available capacity before registering any hits or when all hits time out.
     */
    public function capacityInitial() : int
    {
        return $this->algorithm->capacityInitial();
    }

    /**
     * Time required to fully reset to the total capacity.
     */
    public function resetIn(string $id) : TimeUnit
    {
        return $this->algorithm->resetIn($id, $this->storage);
    }

    /**
     * Try to record next hit, in case of rate limit exception take the cooldown time and sleep current process.
     */
    public function throttle(string $id, Process $process) : void
    {
        try {
            $this->algorithm->hit($id, $this->storage);
        } catch (RateLimitException $rateLimitException) {
            $process->sleep($rateLimitException->retryIn());
            $this->throttle($id, $process);
        }
    }
}
