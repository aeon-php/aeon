<?php

declare(strict_types=1);

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Aeon\Twig\CalendarExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('calendar.twig', CalendarExtension::class)
        ->args([ref('calendar'),  '%aeon.timezone%', '%aeon.datetime_format%', '%aeon.date_format%', '%aeon.time_format%'])
        ->tag('twig.extension', []);
};
