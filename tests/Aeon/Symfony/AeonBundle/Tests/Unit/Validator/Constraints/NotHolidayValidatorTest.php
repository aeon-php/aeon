<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Validator\Constraints;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Holidays\GoogleRegionalHolidaysFactory;
use Aeon\Symfony\AeonBundle\Validator\Constraints\NotHoliday;
use Aeon\Symfony\AeonBundle\Validator\Constraints\NotHolidayValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class NotHolidayValidatorTest extends ConstraintValidatorTestCase
{
    public function test_null_is_valid() : void
    {
        $this->validator->validate(null, new NotHoliday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_empty_string_is_valid() : void
    {
        $this->validator->validate('', new NotHoliday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_invalid_date_string_is_not_valid() : void
    {
        $this->validator->validate('not_valid_date', new NotHoliday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is a holiday.')
            ->setParameter('{{ day }}', '"not_valid_date"')
            ->setCode(NotHoliday::HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_holiday_string_is_not_valid() : void
    {
        $this->validator->validate('2020-01-01', new NotHoliday(['countryCode' => 'PL']));

        $this->buildViolation('Day {{ day }} is a holiday.')
            ->setParameter('{{ day }}', '"2020-01-01"')
            ->setCode(NotHoliday::HOLIDAY_DAY)
            ->assertRaised();
    }

    public function test_that_valid_non_holiday_string_is_valid() : void
    {
        $this->validator->validate('2020-01-02', new NotHoliday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_that_valid_non_holiday_aeon_day_is_valid() : void
    {
        $this->validator->validate(Day::fromString('2020-01-02'), new NotHoliday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    public function test_that_valid_non_holiday_datetime_interface_is_valid() : void
    {
        $this->validator->validate(new \DateTimeImmutable('2020-01-02'), new NotHoliday(['countryCode' => 'PL']));

        $this->assertNoViolation();
    }

    protected function createValidator() : ConstraintValidator
    {
        return new NotHolidayValidator(new GoogleRegionalHolidaysFactory());
    }
}
