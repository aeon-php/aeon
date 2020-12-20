<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    if (\class_exists('Aeon\Calendar\Gregorian\YasumiHolidaysFactory')) {
        $services->set('calendar.holidays.factory.yasumi', 'Aeon\Calendar\YasumiHolidaysFactory');
    }
};
