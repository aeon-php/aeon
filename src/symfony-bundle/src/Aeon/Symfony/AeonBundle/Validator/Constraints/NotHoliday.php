<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class NotHoliday extends Constraint
{
    public const HOLIDAY_DAY = 'a4a2fb95-c359-4683-8fbc-307967dd28a4';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::HOLIDAY_DAY => 'HOLIDAY_DAY',
    ];

    /**
     * @var array<string, string>
     */
    protected static $errorNames = [
        self::HOLIDAY_DAY => 'HOLIDAY_DAY',
    ];

    public string $message = 'Day {{ day }} is a holiday.';

    public string $countryCode;

    public function __construct($options = null)
    {
        if (!isset($options['countryCode'])) {
            throw new MissingOptionsException(
                \sprintf('The option "countryCode" must be set for constraint "%s".', self::class),
                ['countryCode'],
            );
        }

        $this->countryCode = $options['countryCode'];
        unset($options['countryCode']);

        parent::__construct($options);
    }

    public function validatedBy() : string
    {
        return 'calendar.holidays.validator.not_holiday';
    }
}
