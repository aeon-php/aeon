<?php

declare(strict_types=1);

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimiters;
use Aeon\Symfony\AeonBundle\Twig\RateLimiterExtension;
use Aeon\Twig\CalendarExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('calendar.twig', CalendarExtension::class)
        ->args([service('calendar'),  '%aeon.ui_timezone%', '%aeon.ui_datetime_format%', '%aeon.ui_date_format%', '%aeon.ui_time_format%'])
        ->tag('twig.extension', []);

    $services->set('rate_limiter.twig', RateLimiterExtension::class)
        ->args([service(RateLimiters::class)])
        ->tag('twig.extension', []);
};
