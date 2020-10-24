<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\DataTransformer;

use Aeon\Calendar\Gregorian\TimeZone;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

final class AeonTimeZoneToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function transform($value)
    {
        if ($value instanceof TimeZone) {
            return $value->toDateTimeZone();
        }

        return $value;
    }

    public function reverseTransform($value) : ?TimeZone
    {
        if ($value === null) {
            return null;
        }

        if (\is_string($value)) {
            return new TimeZone($value);
        }

        if ($value instanceof \DateTimeZone) {
            return TimeZone::fromDateTimeZone($value);
        }

        if ($value instanceof \IntlTimeZone) {
            return TimeZone::fromDateTimeZone($value->toDateTimeZone());
        }

        throw new UnexpectedTypeException($value, 'The type of $value should be a DateTimeInterface or null.');
    }
}
