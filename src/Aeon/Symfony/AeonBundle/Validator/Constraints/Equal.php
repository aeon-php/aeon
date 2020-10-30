<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class Equal extends AbstractComparison
{
    public const NOT_EQUAL_ERROR = 'c561f511-0fee-4fed-8505-6e67e21aa903';

    protected static $errorNames = [
        self::NOT_EQUAL_ERROR=> 'NOT_EQUAL_ERROR',
    ];

    public $message = 'This value should be equal {{ compared_value }}.';
}
