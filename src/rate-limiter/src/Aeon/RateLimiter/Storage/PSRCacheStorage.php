<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Storage;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Hit;
use Aeon\RateLimiter\Hits;
use Aeon\RateLimiter\Storage;
use Psr\Cache\CacheItemPoolInterface;

final class PSRCacheStorage implements Storage
{
    use StorageIdNormalizer;

    private CacheItemPoolInterface $pool;

    private Calendar $calendar;

    public function __construct(CacheItemPoolInterface $pool, Calendar $calendar)
    {
        $this->pool = $pool;
        $this->calendar = $calendar;
    }

    public function addHit(string $id, TimeUnit $ttl) : void
    {
        $cacheItem = $this->pool->getItem($this->normalize($id));
        /** @var null|string $hitsDataJson */
        $hitsDataJson = $cacheItem->get();

        if ($hitsDataJson === null) {
            $hits = (new Hits(new Hit($id, $this->calendar->now(), $ttl)));
        } else {
            /** @var array<int, array{id: string, datetime: string, ttl: string}> $hitsData */
            $hitsData = \json_decode($hitsDataJson, true);

            $hits = Hits::fromArray($hitsData)
                ->add(new Hit($this->normalize($id), $this->calendar->now(), $ttl))
                ->filterExpired($this->calendar);
        }

        $cacheItem->set(\json_encode($hits->normalize(), JSON_THROW_ON_ERROR));

        $this->pool->save($cacheItem);
    }

    public function all(string $id) : Hits
    {
        $cacheItem = $this->pool->getItem($this->normalize($id));

        /** @var null|string $hitsDataJson */
        $hitsDataJson = $cacheItem->get();

        if ($hitsDataJson === null) {
            return new Hits();
        }

        /** @var array<int, array{id: string, datetime: string, ttl: string}> $hitsData */
        $hitsData = \json_decode($hitsDataJson, true, 512, JSON_THROW_ON_ERROR);

        return Hits::fromArray($hitsData)->filterExpired($this->calendar);
    }

    public function count(string $id) : int
    {
        return $this->all($this->normalize($id))->count();
    }
}
