<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Tests\Unit\Storage;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\RateLimiter\Storage;
use Aeon\RateLimiter\Storage\MemoryStorage;

final class MemoryStorageTest extends StorageTestCase
{
    protected function storage(Calendar $calendar) : Storage
    {
        return new MemoryStorage($calendar);
    }
}
