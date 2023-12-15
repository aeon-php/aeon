<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class After extends AbstractComparison
{
    public const BEFORE_OR_EQUAL_ERROR = '99f63b74-a275-4a01-8678-63124971bff8';

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

    public function __construct(mixed $value = null, string $propertyPath = null, string $message = 'This value should be after {{ compared_value }}.', array $groups = null, mixed $payload = null, array $options = [])
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
