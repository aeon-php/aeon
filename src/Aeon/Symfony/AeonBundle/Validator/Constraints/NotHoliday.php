<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class NotHoliday extends Constraint
{
    public const HOLIDAY_DAY = 'a4a2fb95-c359-4683-8fbc-307967dd28a4';

    protected static $errorNames = [
        self::HOLIDAY_DAY => 'HOLIDAY_DAY',
    ];

    public $message = 'Day {{ day }} is a holiday.';

    public $countryCode;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function getRequiredOptions()
    {
        return ['countryCode'];
    }

    public function validatedBy()
    {
        return 'calendar.holidays.validator.not_holiday';
    }
}
