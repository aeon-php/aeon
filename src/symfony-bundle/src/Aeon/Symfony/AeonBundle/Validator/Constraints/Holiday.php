<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class Holiday extends Constraint
{
    public const NOT_HOLIDAY_DAY = 'a4a2fb95-c359-4683-8fbc-307967dd28a4';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::NOT_HOLIDAY_DAY => 'NOT_HOLIDAY_DAY',
    ];

    /**
     * @var array<string, string>
     */
    protected static $errorNames = [
        self::NOT_HOLIDAY_DAY => 'NOT_HOLIDAY_DAY',
    ];

    public string $message = 'Day {{ day }} is not a holiday.';

    public string $countryCode;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function getRequiredOptions() : array
    {
        return ['countryCode'];
    }

    public function validatedBy() : string
    {
        return 'calendar.holidays.validator.holiday';
    }
}
