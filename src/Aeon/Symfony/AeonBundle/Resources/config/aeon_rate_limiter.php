<?php

declare(strict_types=1);

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Aeon\Symfony\AeonBundle\EventListener\RateLimitRequestListener;
use Aeon\Symfony\AeonBundle\EventListener\RateLimitResponseListener;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimiters;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimitHttpProtocol;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('rate_limiters', RateLimiters::class)
        ->alias(RateLimiters::class, 'rate_limiters')
        ->public();

    $services->set('request_throttling', RequestThrottling::class)
        ->alias(RequestThrottling::class, 'request_throttling')
        ->public();

    $services->set('request.listener.rate_limit', RateLimitRequestListener::class)
        ->args([ref(RateLimitHttpProtocol::class), ref(RequestThrottling::class)])
        ->tag('kernel.event_listener', ['event' => 'kernel.request']);

    $services->set('response.listener.rate_limit', RateLimitResponseListener::class)
        ->args([ref(RateLimitHttpProtocol::class), ref(RequestThrottling::class)])
        ->tag('kernel.event_listener', ['event' => 'kernel.response']);
};
