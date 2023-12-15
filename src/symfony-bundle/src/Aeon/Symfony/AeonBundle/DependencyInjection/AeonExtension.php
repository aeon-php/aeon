<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\DependencyInjection;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\GregorianCalendarStub;
use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Algorithm\LeakyBucketAlgorithm;
use Aeon\RateLimiter\Algorithm\SlidingWindowAlgorithm;
use Aeon\RateLimiter\RateLimiter;
use Aeon\Symfony\AeonBundle\EventListener\RateLimitExceptionListener;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimitHttpProtocol;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy\HeaderRequestIdentificationStrategy;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy\SessionIdRequestIdentificationStrategy;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy\UsernameRequestIdentificationStrategy;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling\RouteThrottle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class AeonExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('aeon_calendar.php');
        $loader->load('aeon_calendar_twig.php');
        $loader->load('aeon_calendar_holidays_google.php');
        $loader->load('aeon_calendar_holidays_yasumi.php');
        $loader->load('aeon_calendar_holidays.php');
        $loader->load('aeon_rate_limiter.php');

        $container->setParameter('aeon.calendar_timezone', $config['calendar_timezone']);
        $container->setParameter('aeon.ui_timezone', $config['ui_timezone']);
        $container->setParameter('aeon.ui_datetime_format', $config['ui_datetime_format']);
        $container->setParameter('aeon.ui_date_format', $config['ui_date_format']);
        $container->setParameter('aeon.ui_time_format', $config['ui_time_format']);

        if ($container->has((string) $config['calendar_holidays_factory_service'])) {
            $container->setAlias('aeon.calendar.holidays.factory', (string) $config['calendar_holidays_factory_service']);
        }

        if ($container->hasParameter('kernel.environment')) {
            if ($container->getParameter('kernel.environment') === 'test') {
                $container->getDefinition('calendar')
                    ->setClass(GregorianCalendarStub::class);

                $container->setAlias(GregorianCalendarStub::class, 'calendar')
                    ->setPublic(true);
            }
        }

        $this->registerRateLimiters($container, $config['rate_limiter']);
        $this->registerRequestThrottling($container, $config['request_throttling']);
    }

    private function registerRateLimiters(ContainerBuilder $container, array $config) : void
    {
        foreach ($config as $rateLimiterConfig) {
            switch ($rateLimiterConfig['algorithm']) {
                case 'leaky_bucket':
                    $leakTimeDefinition = $container
                        ->register('rate_limiter.algorithm.leaky_bucket.leak_time' . $rateLimiterConfig['id'], TimeUnit::class)
                        ->setFactory([TimeUnit::class, 'fromDateString'])
                        ->setArguments([$rateLimiterConfig['configuration']['leak_time']]);

                    $algorithmDefinition = $container
                        ->register('rate_limiter.algorithm.leaky_bucket.' . $rateLimiterConfig['id'], LeakyBucketAlgorithm::class)
                        ->setArguments(
                            [
                                new Reference(Calendar::class),
                                $rateLimiterConfig['configuration']['bucket_size'],
                                $rateLimiterConfig['configuration']['leak_size'],
                                $leakTimeDefinition,
                            ]
                        );

                    $rateLimiterDefinition = $container
                        ->register('rate_limiter.' . $rateLimiterConfig['id'], RateLimiter::class)
                        ->setArguments([
                            $algorithmDefinition,
                            new Reference($rateLimiterConfig['configuration']['storage_service_id']),
                        ]);

                    $container->getDefinition('rate_limiters')->addMethodCall('add', [$rateLimiterConfig['id'], $rateLimiterDefinition]);

                    break;
                case 'sliding_window':
                    $timeWindowDefinition = $container
                        ->register('rate_limiter.algorithm.sliding_window.time_window' . $rateLimiterConfig['id'], TimeUnit::class)
                        ->setFactory([TimeUnit::class, 'fromDateString'])
                        ->setArguments([$rateLimiterConfig['configuration']['time_window']]);

                    $algorithmDefinition = $container
                        ->register('rate_limiter.algorithm.sliding_window.' . $rateLimiterConfig['id'], SlidingWindowAlgorithm::class)
                        ->setArguments(
                            [
                                new Reference(Calendar::class),
                                $rateLimiterConfig['configuration']['limit'],
                                $timeWindowDefinition,
                            ]
                        );

                    $rateLimiterDefinition = $container
                        ->register('rate_limiter.' . $rateLimiterConfig['id'], RateLimiter::class)
                        ->setArguments([
                            $algorithmDefinition,
                            new Reference($rateLimiterConfig['configuration']['storage_service_id']),
                        ]);

                    $container->getDefinition('rate_limiters')->addMethodCall('add', [$rateLimiterConfig['id'], $rateLimiterDefinition]);

                    break;
            }
        }
    }

    private function registerRequestThrottling(ContainerBuilder $container, $config) : void
    {
        if ($config['register_rate_limit_exception_listener'] === true) {
            $container->register('exception.listener.rate_limit', RateLimitExceptionListener::class)
                ->setArguments([new Reference(RateLimitHttpProtocol::class)])
                ->addTag('kernel.event_listener', ['event' => 'kernel.exception']);
        }

        $container->register('rate_limit_http_protocol', RateLimitHttpProtocol::class)
            ->setArguments([
                $config['response_code'],
                $config['response_message'],
                $config['headers']['limit'],
                $config['headers']['remaining'],
                $config['headers']['reset'],
            ]);
        $container->setAlias(RateLimitHttpProtocol::class, 'rate_limit_http_protocol');

        if ($container->hasDefinition('security.token_storage')) {
            $usernameIdentificationStrategyDefinition = $container->register(
                'rate_limiter.request.request_identification_strategy.username',
                UsernameRequestIdentificationStrategy::class
            )->setArguments([new Reference('security.token_storage')]);
        } else {
            $usernameIdentificationStrategyDefinition = null;
        }

        $sessionIdRequestIdentificationStrategy = $container->register(
            'rate_limiter.request.request_identification_strategy.session_id',
            SessionIdRequestIdentificationStrategy::class
        );

        $throttles = [];

        foreach ($config['routes'] as $requestThrottlingConfig) {
            $routeId = \sha1(\json_encode($requestThrottlingConfig));

            switch ($requestThrottlingConfig['request_identification_strategy']['type']) {
                case 'session_id':
                    $requestIdentificationStrategyDefinition = $sessionIdRequestIdentificationStrategy;

                    break;
                case 'username':
                    if ($usernameIdentificationStrategyDefinition === null) {
                        throw new ServiceNotFoundException('security.token_storage');
                    }

                    $requestIdentificationStrategyDefinition = $usernameIdentificationStrategyDefinition;

                    break;
                case 'header':
                    $requestIdentificationStrategyDefinition = $container->register(
                        'rate_limiter.request.request_identification_strategy.header.' . $routeId,
                        HeaderRequestIdentificationStrategy::class
                    )->setArguments([$requestThrottlingConfig['request_identification_strategy']['configuration']['header']]);

                    break;

                default:
                    if (!$container->hasDefinition($requestThrottlingConfig['request_identification_strategy']['type'])) {
                        throw new ServiceNotFoundException($requestThrottlingConfig['request_identification_strategy']['type']);
                    }

                    $requestIdentificationStrategyDefinition = new Reference($requestThrottlingConfig['request_identification_strategy']['type']);

                    break;
            }

            $routeThrottle = $container->register(
                'request_throttling.route.' . $routeId,
                RouteThrottle::class
            )->setArguments([
                $requestThrottlingConfig['route_name'],
                new Reference('rate_limiter.' . $requestThrottlingConfig['rate_limiter_id']),
                $requestThrottlingConfig['methods'],
                $requestIdentificationStrategyDefinition,
            ]);

            $throttles[] = $routeThrottle;
        }

        $container->register('request_throttling', RequestThrottling::class)
            ->setArguments([
                $throttles,
            ]);
    }
}
