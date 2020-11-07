<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Symfony\AeonBundle\Validator\Constraints\AfterOrEqual;
use Aeon\Symfony\AeonBundle\Validator\Constraints\AfterOrEqualValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AfterOrEqualValidatorTest extends AbstractComparisonValidatorTestCase
{
    public function provideValidComparisons() : \Generator
    {
        yield [DateTime::fromString('2005/01/01'), DateTime::fromString('2001/01/01')];
        yield [DateTime::fromString('2005/01/01'), DateTime::fromString('2001/01/01')];
        yield [DateTime::fromString('2001/01/01'), DateTime::fromString('2001/01/01')];
        yield [DateTime::fromString('2005/01/01 UTC'), DateTime::fromString('2001/01/01 UTC')];

        yield [Day::fromString('2005/01/01'), Day::fromString('2001/01/01')];
        yield [Day::fromString('2005/01/01'), Day::fromString('2001/01/01')];
        yield [Day::fromString('2001/01/01'), Day::fromString('2001/01/01')];
        yield [Day::fromString('2005/01/01 UTC'), Day::fromString('2001/01/01 UTC')];
    }

    public function provideInvalidComparisons() : \Generator
    {
        yield [DateTime::fromString('2000/01/01'), '2000-01-01T00:00:00+00:00', DateTime::fromString('2005/01/01'), '2005-01-01T00:00:00+00:00', DateTime::class];
        yield [DateTime::fromString('2000/01/01'), '2000-01-01T00:00:00+00:00', DateTime::fromString('2000/01/02'), '2000-01-02T00:00:00+00:00', DateTime::class];
        yield [DateTime::fromString('2000/01/01'), '2000-01-01T00:00:00+00:00', '2000-01-01T00:00:00+00:00', '"2000-01-01T00:00:00+00:00"', 'string'];
    }

    protected function createValidator() : ConstraintValidator
    {
        return new AfterOrEqualValidator();
    }

    protected function createConstraint(array $options = null) : Constraint
    {
        return new AfterOrEqual($options);
    }

    protected function getErrorCode() : ?string
    {
        return AfterOrEqual::BEFORE_ERROR;
    }
}
