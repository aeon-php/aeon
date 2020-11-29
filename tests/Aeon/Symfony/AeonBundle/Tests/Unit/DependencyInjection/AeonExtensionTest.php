<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\DependencyInjection;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\RateLimiter\RateLimiter;
use Aeon\RateLimiter\Storage\PSRCacheStorage;
use Aeon\Symfony\AeonBundle\DependencyInjection\AeonExtension;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimiters;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling\RouteThrottle;
use Aeon\Symfony\AeonBundle\Twig\RateLimiterExtension;
use Aeon\Twig\CalendarExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

final class AeonExtensionTest extends TestCase
{
    private ?KernelInterface $kernel;

    private ?ContainerBuilder $container;

    protected function setUp() : void
    {
        parent::setUp();

        $this->kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $this->container = new ContainerBuilder();
    }

    protected function tearDown() : void
    {
        parent::tearDown();

        $this->container = null;
        $this->kernel = null;
    }

    public function test_default_configuration() : void
    {
        $extension = new AeonExtension();
        $extension->load(
            [
                [],
            ],
            $this->container
        );

        $this->assertTrue($this->container->hasDefinition('calendar'));
        $this->assertInstanceOf(Calendar::class, $this->container->get('calendar'));

        $this->assertTrue($this->container->hasDefinition('calendar.twig'));
        $this->assertInstanceOf(CalendarExtension::class, $this->container->get('calendar.twig'));
        $this->assertTrue($this->container->getDefinition('calendar.twig')->hasTag('twig.extension'));

        $this->assertTrue($this->container->hasDefinition('rate_limiters'));
        $this->assertInstanceOf(RateLimiters::class, $this->container->get('rate_limiters'));

        $this->assertTrue($this->container->hasDefinition('rate_limiter.twig'));
        $this->assertInstanceOf(RateLimiterExtension::class, $this->container->get('rate_limiter.twig'));
        $this->assertTrue($this->container->getDefinition('rate_limiter.twig')->hasTag('twig.extension'));
    }

    public function test_rate_limiters_leaky_bucket() : void
    {
        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test',
                            'algorithm' => 'leaky_bucket',
                            'configuration' => [
                                'bucket_size' => 5,
                                'leak_size' => 2,
                                'leak_time' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $this->assertInstanceOf(RateLimiter::class, $this->container->get('rate_limiters')->get('test'));
    }

    public function test_rate_limiters_sliding_window() : void
    {
        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test',
                            'algorithm' => 'sliding_window',
                            'configuration' => [
                                'limit' => 5,
                                'time_window' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $this->assertInstanceOf(RateLimiter::class, $this->container->get('rate_limiters')->get('test'));
    }

    public function test_request_throttling_session_id_identification_strategy() : void
    {
        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test_limiter',
                            'algorithm' => 'sliding_window',
                            'configuration' => [
                                'limit' => 5,
                                'time_window' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                    'request_throttling' => [
                        'routes' => [
                            $routeConfig = [
                                'route_name' => 'test_route',
                                'rate_limiter_id' => 'test_limiter',
                                'methods' => ['POST', 'GET'],
                                'request_identification_strategy' => [
                                    'type' => 'session_id',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $routeId = \sha1(\json_encode($routeConfig));

        $this->assertInstanceOf(RouteThrottle::class, $this->container->get('request_throttling.route.' . $routeId));
    }

    public function test_request_throttling_hedaer_identification_strategy() : void
    {
        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test_limiter',
                            'algorithm' => 'sliding_window',
                            'configuration' => [
                                'limit' => 5,
                                'time_window' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                    'request_throttling' => [
                        'routes' => [
                            $routeConfig = [
                                'route_name' => 'test_route',
                                'rate_limiter_id' => 'test_limiter',
                                'methods' => ['POST', 'GET'],
                                'request_identification_strategy' => [
                                    'type' => 'header',
                                    'configuration' => [
                                        'header' => 'authentication',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $routeId = \sha1(\json_encode($routeConfig));

        $this->assertInstanceOf(RouteThrottle::class, $this->container->get('request_throttling.route.' . $routeId));
    }

    public function test_request_throttling_hedaer_identification_strategy_without_header_configuration() : void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid configuration for path "aeon.request_throttling.routes.0.request_identification_strategy": header request identification strategy requires "headers" option to be configured in "configuration" section');

        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test_limiter',
                            'algorithm' => 'sliding_window',
                            'configuration' => [
                                'limit' => 5,
                                'time_window' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                    'request_throttling' => [
                        'routes' => [
                            $routeConfig = [
                                'route_name' => 'test_route',
                                'rate_limiter_id' => 'test_limiter',
                                'methods' => ['POST', 'GET'],
                                'request_identification_strategy' => [
                                    'type' => 'header',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $routeId = \sha1(\json_encode($routeConfig));

        $this->assertInstanceOf(RouteThrottle::class, $this->container->get('request_throttling.route.' . $routeId));
    }

    public function test_request_throttling_username_identification_strategy() : void
    {
        $this->container->set('psr.cache.array', new PSRCacheStorage(new ArrayAdapter(), GregorianCalendar::UTC()));
        $this->container->register('security.token_storage', TokenStorage::class);

        $extension = new AeonExtension();
        $extension->load(
            [
                'aeon' => [
                    'rate_limiter' => [
                        [
                            'id' => 'test_limiter',
                            'algorithm' => 'sliding_window',
                            'configuration' => [
                                'limit' => 5,
                                'time_window' => '1 minute',
                                'storage_service_id' => 'psr.cache.array',
                            ],
                        ],
                    ],
                    'request_throttling' => [
                        'routes' => [
                            $routeConfig = [
                                'route_name' => 'test_route',
                                'rate_limiter_id' => 'test_limiter',
                                'methods' => ['POST', 'GET'],
                                'request_identification_strategy' => [
                                    'type' => 'username',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->container
        );

        $routeId = \sha1(\json_encode($routeConfig));

        $this->assertInstanceOf(RouteThrottle::class, $this->container->get('request_throttling.route.' . $routeId));
    }
}
