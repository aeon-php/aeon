<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class AbstractComparisonValidatorTestCase extends ConstraintValidatorTestCase
{
    /**
     * @dataProvider provideValidComparisons
     *
     * @param mixed $dirtyValue
     * @param mixed $comparisonValue
     */
    public function testValidComparisonToValue($dirtyValue, $comparisonValue) : void
    {
        $constraint = $this->createConstraint(['value' => $comparisonValue]);

        $this->validator->validate($dirtyValue, $constraint);

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideInvalidComparisons
     *
     * @param mixed $dirtyValue
     * @param mixed $dirtyValueAsString
     * @param mixed $comparedValue
     * @param mixed $comparedValueString
     * @param string $comparedValueType
     */
    public function testInvalidComparisonToValue($dirtyValue, $dirtyValueAsString, $comparedValue, $comparedValueString, $comparedValueType) : void
    {
        $constraint = $this->createConstraint(['value' => $comparedValue]);
        $constraint->message = 'Constraint Message';

        $this->validator->validate($dirtyValue, $constraint);

        $this->buildViolation('Constraint Message')
            ->setParameter('{{ value }}', $dirtyValueAsString)
            ->setParameter('{{ compared_value }}', $comparedValueString)
            ->setParameter('{{ compared_value_type }}', $comparedValueType)
            ->setCode($this->getErrorCode())
            ->assertRaised();
    }

    public function provideInvalidComparisons() : \Generator
    {
        yield [];
    }

    public function provideValidComparisons() : \Generator
    {
        yield [];
    }

    abstract protected function createConstraint(array $options = null) : Constraint;

    protected function getErrorCode() : ?string
    {
        return null;
    }
}
