<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Tests\Unit;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Hit;
use PHPUnit\Framework\TestCase;

final class HitTest extends TestCase
{
    public function test_expire_when_hit_is_not_expired() : void
    {
        $hit = new Hit('id', DateTime::fromString('2020-01-01 00:00:00 UTC'), TimeUnit::minute());
        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $this->assertFalse($hit->expired($calendar));
    }

    public function test_expire_when_hit_is_expired() : void
    {
        $hit = new Hit('id', DateTime::fromString('2020-01-01 00:00:00 UTC'), TimeUnit::minute());
        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:01:01 UTC'));

        $this->assertTrue($hit->expired($calendar));
    }

    public function test_ttl_left() : void
    {
        $hit = new Hit('id', DateTime::fromString('2020-01-01 00:00:00 UTC'), TimeUnit::minute());

        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:01:01 UTC'));

        $this->assertSame(0, $hit->ttlLeft($calendar)->inSeconds());
    }

    public function test_ttl_left_after_expiration_date() : void
    {
        $hit = new Hit('id', DateTime::fromString('2020-01-01 00:00:00 UTC'), TimeUnit::minute());

        $calendar = new GregorianCalendarStub(TimeZone::UTC());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:30 UTC'));

        $this->assertSame(30, $hit->ttlLeft($calendar)->inSeconds());
    }
}
