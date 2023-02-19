<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Storage;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Hit;
use Aeon\RateLimiter\Hits;
use Aeon\RateLimiter\Storage;

final class MemoryStorage implements Storage
{
    use StorageIdNormalizer;

    private Calendar $calendar;

    /**
     * @var array<string, Hits>
     */
    private array $hits;

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
        $this->hits = [];
    }

    public function addHit(string $id, TimeUnit $ttl) : void
    {
        if (!$this->hasFor($id)) {
            $this->hits[$this->normalize($id)] = new Hits(new Hit($id, $this->calendar->now(), $ttl));
        } else {
            $this->hits[$this->normalize($id)] = $this->hits($this->normalize($id))
                ->add(new Hit($id, $this->calendar->now(), $ttl))
                ->filterExpired($this->calendar);
        }
    }

    public function all(string $id) : Hits
    {
        return $this->hits($id);
    }

    public function count(string $id) : int
    {
        return $this->hits($id)->count();
    }

    private function hits(string $id) : Hits
    {
        if (!$this->hasFor($id)) {
            return new Hits();
        }

        return $this->hits[$this->normalize($id)]->filterExpired($this->calendar);
    }

    private function hasFor(string $id) : bool
    {
        if (!\array_key_exists($this->normalize($id), $this->hits)) {
            return false;
        }

        return true;
    }
}
