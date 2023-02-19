<?php declare(strict_types=1);

namespace Aeon\RateLimiter;

use Aeon\Calendar\TimeUnit;

interface Storage
{
    public function addHit(string $id, TimeUnit $ttl) : void;

    public function all(string $id) : Hits;

    public function count(string $id) : int;
}
