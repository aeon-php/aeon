<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\DataTransformer;

use Aeon\Calendar\Gregorian\Day;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

final class AeonDayToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function transform($value)
    {
        if ($value instanceof DAy) {
            return $value->toDateTimeImmutable();
        }

        return $value;
    }

    public function reverseTransform($value) : ?Day
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Day::fromDateTime($value);
        }

        if (\is_string($value)) {
            return Day::fromString($value);
        }

        if (\is_array($value)) {
            return Day::create(
                (int) $value['year'],
                (int) $value['month'],
                (int) $value['day'],
            );
        }

        if (\is_int($value)) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($value);

            return Day::fromDateTime($dateTime);
        }

        throw new UnexpectedTypeException($value, 'The type of $value should be a DateTimeInterface or null.');
    }
}
