<?php

declare(strict_types=1);

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\TimeZone;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('timezone', TimeZone::class)
        ->args(['%aeon.timezone%'])
        ->alias(TimeZone::class, 'timezone')
        ->public();

    $services->set('calendar', GregorianCalendar::class)
        ->args([ref('timezone')])
        ->public()
        ->alias(Calendar::class, 'calendar')
        ->public();
};
