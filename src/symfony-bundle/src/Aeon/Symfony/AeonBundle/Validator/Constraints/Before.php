<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class Before extends AbstractComparison
{
    public const BEFORE_OR_EQUAL_ERROR = 'c561f511-0fee-4fed-8505-6e67e21aa903';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::BEFORE_OR_EQUAL_ERROR => 'BEFORE_OR_EQUAL_ERROR',
    ];

    /**
     * @var array<string, string>
     */
    protected static $errorNames = [
        self::BEFORE_OR_EQUAL_ERROR => 'BEFORE_OR_EQUAL_ERROR',
    ];

    public function __construct(mixed $value = null, string $propertyPath = null, string $message = 'This value should be before {{ compared_value }}.', array $groups = null, mixed $payload = null, array $options = [])
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
