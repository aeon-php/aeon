<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class AbstractComparisonValidatorTestCase extends ConstraintValidatorTestCase
{
    public static function provideInvalidComparisons() : \Generator
    {
        yield [];
    }

    public static function provideValidComparisons() : \Generator
    {
        yield [];
    }

    /**
     * @param mixed $dirtyValue
     * @param mixed $comparisonValue
     */
    #[DataProvider('provideValidComparisons')]
    public function testValidComparisonToValue($dirtyValue, $comparisonValue) : void
    {
        $constraint = $this->createConstraint($comparisonValue);

        $this->validator->validate($dirtyValue, $constraint);

        $this->assertNoViolation();
    }

    /**
     * @param mixed $dirtyValue
     * @param mixed $dirtyValueAsString
     * @param mixed $comparedValue
     * @param mixed $comparedValueString
     * @param string $comparedValueType
     */
    #[DataProvider('provideInvalidComparisons')]
    public function testInvalidComparisonToValue($dirtyValue, $dirtyValueAsString, $comparedValue, $comparedValueString, $comparedValueType) : void
    {
        $constraint = $this->createConstraint($comparedValue);
        $constraint->message = 'Constraint Message';

        $this->validator->validate($dirtyValue, $constraint);

        $this->buildViolation('Constraint Message')
            ->setParameter('{{ value }}', $dirtyValueAsString)
            ->setParameter('{{ compared_value }}', $comparedValueString)
            ->setParameter('{{ compared_value_type }}', $comparedValueType)
            ->setCode($this->getErrorCode())
            ->assertRaised();
    }

    abstract protected function createConstraint(mixed $value) : Constraint;

    protected function getErrorCode() : ?string
    {
        return null;
    }
}
