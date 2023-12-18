<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

final class AfterOrEqual extends AbstractComparison
{
    public const BEFORE_ERROR = '1c6d2666-52d7-4131-bd11-3f90e2120c2d';

    protected static $errorNames = [
        self::BEFORE_ERROR => 'BEFORE_ERROR',
    ];

    public $message = 'This value should be after or equal {{ compared_value }}.';
}
