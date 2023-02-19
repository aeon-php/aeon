<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class Before extends AbstractComparison
{
    public const BEFORE_OR_EQUAL_ERROR = 'c561f511-0fee-4fed-8505-6e67e21aa903';

    protected static $errorNames = [
        self::BEFORE_OR_EQUAL_ERROR => 'BEFORE_OR_EQUAL_ERROR',
    ];

    public $message = 'This value should be before {{ compared_value }}.';
}
