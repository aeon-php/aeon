<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\DataTransformer;

use Aeon\Calendar\Gregorian\DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

final class AeonDateTimeToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function transform($value)
    {
        if ($value instanceof DateTime) {
            return $value->toDateTimeImmutable();
        }

        return $value;
    }

    public function reverseTransform($value) : ?DateTime
    {
        if ($value === null) {
            return null;
        }

        if ($value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return DateTime::fromDateTime($value);
        }

        if (\is_string($value)) {
            return DateTime::fromString($value);
        }

        if (\is_array($value)) {
            return DateTime::create(
                (int) $value['year'],
                (int) $value['month'],
                (int) $value['day'],
                isset($value['hour']) && \ctype_digit((string) $value['hour']) ? (int) $value['hour'] : 0,
                isset($value['minute']) && \ctype_digit((string) $value['minute']) ? (int) $value['minute'] : 0,
                isset($value['second']) && \ctype_digit((string) $value['second']) ? (int) $value['second'] : 0
            );
        }

        if (\is_int($value)) {
            return DateTime::fromTimestampUnix($value);
        }

        throw new UnexpectedTypeException($value, 'The type of $value should be a DateTimeInterface or null.');
    }
}
