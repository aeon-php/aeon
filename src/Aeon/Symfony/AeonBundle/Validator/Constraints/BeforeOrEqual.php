<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class BeforeOrEqual extends AbstractComparison
{
    public const AFTER_ERROR = 'c411b575-c9fd-4e22-af8a-2e23a565d9a4';

    protected static $errorNames = [
        self::AFTER_ERROR => 'AFTER_ERROR',
    ];

    public $message = 'This value should be before or equal {{ compared_value }}.';
}
