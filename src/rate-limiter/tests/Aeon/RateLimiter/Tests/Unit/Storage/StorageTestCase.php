<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Tests\Unit\Storage;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Storage;
use PHPUnit\Framework\TestCase;

abstract class StorageTestCase extends TestCase
{
    public function test_empty_storage() : void
    {
        $storage = $this->storage($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $this->assertSame($storage->count('id'), 0);
    }

    public function test_storage_with_hits() : void
    {
        $storage = $this->storage($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $storage->addHit('id', TimeUnit::minute());
        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(5)));
        $storage->addHit('id', TimeUnit::minute());

        $this->assertSame($storage->count('id'), 2);
    }

    public function test_storage_with_non_unicode_id() : void
    {
        $storage = $this->storage($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $storage->addHit('ÄÓ', TimeUnit::minute());
        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(5)));
        $storage->addHit('ÄÓ', TimeUnit::minute());

        $this->assertSame($storage->count('Äó'), 2);
    }

    public function test_storage_with_all_non_expired_hits() : void
    {
        $storage = $this->storage($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $storage->addHit('id', TimeUnit::minute());

        $calendar->setNow($calendar->now()->add(TimeUnit::minutes(4)));

        $storage->addHit('id', TimeUnit::minute());

        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(59)));

        $this->assertSame($storage->all('id')->count(), 1);
    }

    public function test_storage_hits_ttl() : void
    {
        $storage = $this->storage($calendar = new GregorianCalendarStub(TimeZone::UTC()));

        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $storage->addHit('id', TimeUnit::minute());
        $storage->addHit('id', TimeUnit::minutes(5));

        $calendar->setNow($calendar->now()->add(TimeUnit::minutes(4)));

        $this->assertSame($storage->count('id'), 1);
    }

    abstract protected function storage(Calendar $calendar) : Storage;
}
