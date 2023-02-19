<?php

declare(strict_types=1);

namespace Aeon\RateLimiter\Tests\Unit\Algorithm;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Algorithm\SlidingWindowAlgorithm;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\RateLimiter\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

final class SlidingWindowAlgorithmTest extends TestCase
{
    public function test_with_available_hits() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 2, TimeUnit::minute());
        $algorithm->hit('hit_id', $memoryStorage = new MemoryStorage($calendar));

        $this->assertSame($algorithm->estimate('hit_id', $memoryStorage)->inSeconds(), 0);
    }

    public function test_without_available_hits() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::minute());
        $algorithm->hit('hit_id', $memoryStorage = new MemoryStorage($calendar));

        $this->assertSame($algorithm->estimate('hit_id', $memoryStorage)->inSeconds(), 59);
    }

    public function test_hit_without_available_hits() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::minute());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $algorithm->hit('hit_id', $memoryStorage = new MemoryStorage($calendar));

        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('Execution "hit_id" was limited for the next 60.000000 seconds');
        $this->expectExceptionCode(0);

        $algorithm->hit('hit_id', $memoryStorage);
    }

    public function test_hit_without_available_hits_with_additional_milliseconds() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::seconds(1)->add(TimeUnit::milliseconds(500)));
        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $algorithm->hit('hit_id', $memoryStorage = new MemoryStorage($calendar));

        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('Execution "hit_id" was limited for the next 0.500000 seconds');
        $this->expectExceptionCode(0);

        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(1)));

        $algorithm->hit('hit_id', $memoryStorage);
    }

    public function test_hit_without_available_hits_exception() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::minute());
        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $algorithm->hit('hit_id', $memoryStorage = new MemoryStorage($calendar));

        try {
            $algorithm->hit('hit_id', $memoryStorage);
        } catch (RateLimitException $e) {
            $this->assertSame('hit_id', $e->id());
            $this->assertEquals(TimeUnit::minute(), $e->retryIn());
        }
    }

    public function test_resetting_hits() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::minute());

        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $this->assertSame(1, $algorithm->capacity('hit_id', $storage = new MemoryStorage($calendar)));
        $this->assertSame(1, $algorithm->capacityInitial());
        $this->assertSame(0, $algorithm->resetIn('hit_id', $storage)->inSeconds());

        $algorithm->hit('hit_id', $storage);
        $this->assertSame(0, $algorithm->capacity('hit_id', $storage));

        $this->assertSame($algorithm->estimate('hit_id', $storage)->inSeconds(), 60);

        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(61)));

        $this->assertSame(1, $algorithm->capacity('hit_id', $storage));
        $this->assertSame($algorithm->estimate('hit_id', $storage)->inSeconds(), 0);

        $algorithm->hit('hit_id', $storage);

        $this->assertSame(0, $algorithm->capacity('hit_id', $storage));
        $this->assertSame($algorithm->estimate('hit_id', $storage)->inSeconds(), 60);

        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(61)));

        $this->assertSame(1, $algorithm->capacity('hit_id', $storage));
        $this->assertSame($algorithm->estimate('hit_id', $storage)->inSeconds(), 0);

        $algorithm->hit('hit_id', $storage);
    }

    public function test_resets_in() : void
    {
        $algorithm = new SlidingWindowAlgorithm($calendar = new GregorianCalendarStub(TimeZone::UTC()), 1, TimeUnit::minute());

        $calendar->setNow(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $this->assertSame(1, $algorithm->capacity('hit_id', $storage = new MemoryStorage($calendar)));
        $algorithm->hit('hit_id', $storage);
        $this->assertSame(0, $algorithm->capacity('hit_id', $storage));
        $this->assertSame(60, $algorithm->resetIn('hit_id', $storage)->inSeconds());

        $calendar->setNow($calendar->now()->add(TimeUnit::seconds(61)));

        $this->assertSame(1, $algorithm->capacity('hit_id', $storage));
    }
}
