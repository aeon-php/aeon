<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Holidays\GoogleRegionalHolidaysFactory;
use Aeon\Symfony\AeonBundle\Validator\Constraints\Holiday;
use Aeon\Symfony\AeonBundle\Validator\Constraints\HolidayValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class HolidayValidatorTest extends ConstraintValidatorTestCase
{
    public function test_null_is_valid() : void
    {
        $this->validator->validate(null, new Holiday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_empty_string_is_valid() : void
    {
        $this->validator->validate('', new Holiday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_invalid_date_string_is_not_valid() : void
    {
        $this->validator->validate('not_valid_date', new Holiday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is not a holiday.')
            ->setParameter('{{ day }}', '"not_valid_date"')
            ->setCode(Holiday::NOT_HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_holiday_string_is_valid() : void
    {
        $this->validator->validate('2020-01-01', new Holiday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_that_valid_non_holiday_string_is_not_valid() : void
    {
        $this->validator->validate('2020-01-02', new Holiday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is not a holiday.')
            ->setParameter('{{ day }}', '"2020-01-02"')
            ->setCode(Holiday::NOT_HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_non_holiday_aeon_day_is_not_valid() : void
    {
        $this->validator->validate(Day::fromString('2020-01-02'), new Holiday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is not a holiday.')
            ->setParameter('{{ day }}', '"2020-01-02"')
            ->setCode(Holiday::NOT_HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_non_holiday_datetime_interface_is_not_valid() : void
    {
        $this->validator->validate(new \DateTimeImmutable('2020-01-02'), new Holiday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is not a holiday.')
            ->setParameter('{{ day }}', '"2020-01-02"')
            ->setCode(Holiday::NOT_HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_holiday_aeon_day_is_valid() : void
    {
        $this->validator->validate(Day::fromString('2020-01-01'), new Holiday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_that_valid_holiday_datetime_interface_is_valid() : void
    {
        $this->validator->validate(new \DateTimeImmutable('2020-01-01'), new Holiday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    protected function createValidator() : ConstraintValidator
    {
        return new HolidayValidator(new GoogleRegionalHolidaysFactory());
    }
}
