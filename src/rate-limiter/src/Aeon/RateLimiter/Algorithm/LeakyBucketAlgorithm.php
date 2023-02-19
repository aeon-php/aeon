<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Algorithm;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Algorithm;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\RateLimiter\Storage;

final class LeakyBucketAlgorithm implements Algorithm
{
    private Calendar $calendar;

    private int $bucketSize;

    private int $leakSize;

    private TimeUnit $leakTime;

    public function __construct(Calendar $calendar, int $bucketSize, int $leakSize, TimeUnit $leakTime)
    {
        $this->calendar = $calendar;
        $this->bucketSize = $bucketSize;
        $this->leakSize = $leakSize;
        $this->leakTime = $leakTime;
    }

    public function capacityInitial() : int
    {
        return $this->bucketSize;
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function hit(string $id, Storage $storage) : void
    {
        $hits = $storage->all($id);

        if ($hits->count() >= $this->bucketSize) {
            throw new RateLimitException(
                $id,
                $this->capacityInitial(),
                /** @phpstan-ignore-next-line */
                $hits->oldest()->ttlLeft($this->calendar),
                $this->resetIn($id, $storage)
            );
        }

        $ttl = TimeUnit::precise(
            \floor($hits->count() / $this->leakSize) * (float) $this->leakTime->inSecondsPrecise() + (float) $this->leakTime->inSecondsPrecise()
        );

        $storage->addHit($id, $ttl);
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function estimate(string $id, Storage $storage) : TimeUnit
    {
        $hits = $storage->all($id);

        if ($hits->count() >= $this->bucketSize) {
            /** @phpstan-ignore-next-line */
            return $hits->oldest()->ttlLeft($this->calendar);
        }

        return TimeUnit::seconds(0);
    }

    public function capacity(string $id, Storage $storage) : int
    {
        $hits = $storage->all($id);

        return $this->bucketSize - $hits->count();
    }

    /**
     * @psalm-suppress InvalidNullableReturnType
     * @psalm-suppress NullableReturnStatement
     */
    public function resetIn(string $id, Storage $storage) : TimeUnit
    {
        $hits = $storage->all($id);

        /** @phpstan-ignore-next-line */
        return $hits->count() ? $hits->longestTTL($this->calendar) : TimeUnit::seconds(0);
    }
}
