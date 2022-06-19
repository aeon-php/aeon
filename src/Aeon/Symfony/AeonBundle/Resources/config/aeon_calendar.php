<?php

declare(strict_types=1);

use function Aeon\Symfony\AeonBundle\DependencyInjection\Loader\Configurator\service;
use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\TimeZone;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('calendar_timezone', TimeZone::class)
        ->factory([TimeZone::class, 'fromString'])
        ->args(['%aeon.calendar_timezone%'])
        ->alias(TimeZone::class, 'calendar_timezone')
        ->public();

    $services->set('calendar', GregorianCalendar::class)
        ->args([service('calendar_timezone')])
        ->public()
        ->alias(Calendar::class, 'calendar')
        ->public();
};
