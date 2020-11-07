<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Validator\Constraints;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\HolidaysFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class NotHolidayValidator extends ConstraintValidator
{
    private HolidaysFactory $factory;

    public function __construct(HolidaysFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint) : void
    {
        if (!$constraint instanceof NotHoliday) {
            throw new UnexpectedTypeException($constraint, NotHoliday::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value) && !$value instanceof Day && !$value instanceof \DateTimeInterface) {
            \var_dump($value);

            throw new UnexpectedValueException($value, 'string or ' . Day::class);
        }

        try {
            if (\is_string($value)) {
                $day = Day::fromString($value);
            } elseif ($value instanceof \DateTimeInterface) {
                $day = Day::fromDateTime($value);
            } else {
                $day = $value;
            }

            $holidays = $this->factory->create($constraint->countryCode);

            if (!$holidays->isHoliday($day)) {
                return;
            }

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ day }}', $this->formatValue($day->toString()))
                ->setCode(NotHoliday::HOLIDAY_DAY)
                ->addViolation();

            return;
        } catch (\Exception $exception) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ day }}', $this->formatValue($value))
                ->setCode(NotHoliday::HOLIDAY_DAY)
                ->addViolation();

            return;
        }
    }
}
