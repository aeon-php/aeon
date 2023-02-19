<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Tests\Unit\Storage;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\RateLimiter\Storage;
use Aeon\RateLimiter\Storage\PSRCacheStorage;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class PSRStorageTest extends StorageTestCase
{
    protected function storage(Calendar $calendar) : Storage
    {
        return new PSRCacheStorage(new ArrayAdapter(), $calendar);
    }
}
