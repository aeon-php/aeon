<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class BeforeOrEqual extends AbstractComparison
{
    public const AFTER_ERROR = 'c411b575-c9fd-4e22-af8a-2e23a565d9a4';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::AFTER_ERROR => 'AFTER_ERROR',
    ];

    /**
     * @var array<string, string>
     */
    protected static $errorNames = [
        self::AFTER_ERROR => 'AFTER_ERROR',
    ];

    public function __construct(mixed $value = null, string $propertyPath = null, string $message = 'This value should be before or equal {{ compared_value }}.', array $groups = null, mixed $payload = null, array $options = [])
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
