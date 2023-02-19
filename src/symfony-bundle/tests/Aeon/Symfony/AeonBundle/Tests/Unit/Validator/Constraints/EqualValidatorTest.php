<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Symfony\AeonBundle\Validator\Constraints\Equal;
use Aeon\Symfony\AeonBundle\Validator\Constraints\EqualValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class EqualValidatorTest extends AbstractComparisonValidatorTestCase
{
    public function provideValidComparisons() : \Generator
    {
        yield [DateTime::fromString('2001/01/01'), DateTime::fromString('2001/01/01')];
        yield [DateTime::fromString('2001/01/01'), DateTime::fromString('2001/01/01')];
        yield [DateTime::fromString('2001/01/01 UTC'), DateTime::fromString('2001/01/01 UTC')];

        yield [Day::fromString('2001/01/01'), Day::fromString('2001/01/01')];
        yield [Day::fromString('2001/01/01'), Day::fromString('2001/01/01')];
        yield [Day::fromString('2001/01/01 UTC'), Day::fromString('2001/01/01 UTC')];
    }

    public function provideInvalidComparisons() : \Generator
    {
        yield [DateTime::fromString('2010/01/01'), '2010-01-01T00:00:00+00:00', DateTime::fromString('2001/01/01'), '2001-01-01T00:00:00+00:00', DateTime::class];
        yield [DateTime::fromString('2000/01/01'), '2000-01-01T00:00:00+00:00', DateTime::fromString('2003/01/01'), '2003-01-01T00:00:00+00:00', DateTime::class];
        yield [DateTime::fromString('2000/01/01'), '2000-01-01T00:00:00+00:00', '2000-01-01T00:00:00+00:00', '"2000-01-01T00:00:00+00:00"', 'string'];
    }

    protected function createValidator() : ConstraintValidator
    {
        return new EqualValidator();
    }

    protected function createConstraint(array $options = null) : Constraint
    {
        return new Equal($options);
    }

    protected function getErrorCode() : ?string
    {
        return Equal::NOT_EQUAL_ERROR;
    }
}
