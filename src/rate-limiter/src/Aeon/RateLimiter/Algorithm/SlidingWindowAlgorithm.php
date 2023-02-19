<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Algorithm;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Algorithm;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\RateLimiter\Storage;

final class SlidingWindowAlgorithm implements Algorithm
{
    private Calendar $calendar;

    private int $limit;

    private TimeUnit $timeWindow;

    public function __construct(Calendar $calendar, int $limit, TimeUnit $timeWindow)
    {
        $this->calendar = $calendar;
        $this->limit = $limit;
        $this->timeWindow = $timeWindow;
    }

    public function capacityInitial() : int
    {
        return $this->limit;
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function hit(string $id, Storage $storage) : void
    {
        $hits = $storage->all($id);

        if ($hits->count() >= $this->limit) {
            throw new RateLimitException(
                $id,
                $this->capacityInitial(),
                /** @phpstan-ignore-next-line */
                $hits->oldest()->ttlLeft($this->calendar),
                $this->resetIn($id, $storage)
            );
        }

        $storage->addHit($id, $this->timeWindow);
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function estimate(string $id, Storage $storage) : TimeUnit
    {
        $hits = $storage->all($id);

        if ($hits->count() >= $this->limit) {
            /** @phpstan-ignore-next-line */
            return $hits->oldest()->ttlLeft($this->calendar);
        }

        return TimeUnit::seconds(0);
    }

    public function capacity(string $id, Storage $storage) : int
    {
        $hits = $storage->all($id);

        return $this->limit - $hits->count();
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
