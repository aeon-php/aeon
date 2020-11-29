<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional;

use Aeon\Symfony\AeonBundle\Tests\Functional\App\TestAppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ThrottleRequestTest extends WebTestCase
{
    public function test_throttled_endpoint() : void
    {
        $client = self::createClient();
        $client->disableReboot();

        $client->request('POST', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(4, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);

        $client->request('POST', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(3, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);

        $client->request('POST', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(2, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);

        $client->request('POST', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(1, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);

        $client->request('POST', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(0, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);

        $client->request('POST', '/throttle');

        $this->assertSame(429, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertSame('Rate limit exceeded', $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(0, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);
    }

    public function test_throttled_endpoint_with_not_throttled_method() : void
    {
        $client = self::createClient();

        $client->request('GET', '/throttle');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertFalse($client->getResponse()->headers->has('x-ratelimit-limit'));
        $this->assertFalse($client->getResponse()->headers->has('x-ratelimit-remaining'));
        $this->assertFalse($client->getResponse()->headers->has('x-ratelimit-reset'));
    }

    public function test_manually_throttled_endpoint() : void
    {
        $client = self::createClient();

        $client->request('GET', '/manual-throttle');

        $this->assertSame(429, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertSame('Rate limit exceeded', $client->getResponse()->getContent());

        $this->assertSame(5, (int) $client->getResponse()->headers->get('x-ratelimit-limit'));
        $this->assertSame(0, (int) $client->getResponse()->headers->get('x-ratelimit-remaining'));
        $this->assertEqualsWithDelta(10, (int) $client->getResponse()->headers->get('x-ratelimit-reset'), 2);
    }

    protected static function getKernelClass()
    {
        return TestAppKernel::class;
    }
}
