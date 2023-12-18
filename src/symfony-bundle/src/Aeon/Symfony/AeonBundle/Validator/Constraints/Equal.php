<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class Equal extends AbstractComparison
{
    public const NOT_EQUAL_ERROR = 'c561f511-0fee-4fed-8505-6e67e21aa903';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::NOT_EQUAL_ERROR => 'NOT_EQUAL_ERROR',
    ];

    /**
     * @var array<string, string>
     */
    protected static $errorNames = [
        self::NOT_EQUAL_ERROR => 'NOT_EQUAL_ERROR',
    ];

    public function __construct(mixed $value = null, string $propertyPath = null, string $message = 'This value should be equal {{ compared_value }}.', array $groups = null, mixed $payload = null, array $options = [])
    {
        parent::__construct(
            $value,
            $propertyPath,
            $message,
            $groups,
            $payload,
            $options
        );
    }
}
