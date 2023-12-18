<?php

declare(strict_types=1);

use function Aeon\Symfony\AeonBundle\DependencyInjection\Loader\Configurator\service;
use Aeon\Symfony\AeonBundle\Validator\Constraints\HolidayValidator;
use Aeon\Symfony\AeonBundle\Validator\Constraints\NotHolidayValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();

    $services->set('calendar.holidays.validator.not_holiday', NotHolidayValidator::class)
        ->args([service('aeon.calendar.holidays.factory')])
        ->tag('validator.constraint_validator', ['alias' => 'calendar.holidays.validator.not_holiday'])
        ->alias(NotHolidayValidator::class, 'calendar.holidays.validator.not_holiday');

    $services->set('calendar.holidays.validator.holiday', HolidayValidator::class)
        ->args([service('aeon.calendar.holidays.factory')])
        ->tag('validator.constraint_validator', ['alias' => 'calendar.holidays.validator.holiday'])
        ->alias(HolidayValidator::class, 'calendar.holidays.validator.holiday');
};
