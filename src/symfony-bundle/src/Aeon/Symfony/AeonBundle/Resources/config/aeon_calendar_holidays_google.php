<?php

declare(strict_types=1);

use Aeon\Calendar\Holidays\GoogleRegionalHolidaysFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('calendar.holidays.factory.google', GoogleRegionalHolidaysFactory::class)
        ->alias(GoogleRegionalHolidaysFactory::class, 'calendar.holidays.factory.google');
};
