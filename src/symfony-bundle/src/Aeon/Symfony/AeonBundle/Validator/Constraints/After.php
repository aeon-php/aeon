<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class After extends AbstractComparison
{
    public const BEFORE_OR_EQUAL_ERROR = '99f63b74-a275-4a01-8678-63124971bff8';

    protected static $errorNames = [
        self::BEFORE_OR_EQUAL_ERROR => 'BEFORE_OR_EQUAL_ERROR',
    ];

    public $message = 'This value should be after {{ compared_value }}.';
}
