<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\DataTransformer;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Time;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

final class AeonTimeToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function transform($value)
    {
        if ($value instanceof Time) {
            return new \DateTimeImmutable($value->toString());
        }

        return $value;
    }

    public function reverseTransform($value) : ?Time
    {
        if ($value === null) {
            return null;
        }

        if ($value === '') {
            return null;
        }

        if (\is_string($value)) {
            return Time::fromString($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return Time::fromDateTime($value);
        }

        if (\is_array($value)) {
            return new Time(
                isset($value['hour']) && \ctype_digit((string) $value['hour']) ? (int) $value['hour'] : 0,
                isset($value['minute']) && \ctype_digit((string) $value['minute']) ? (int) $value['minute'] : 0,
                isset($value['second']) && \ctype_digit((string) $value['second']) ? (int) $value['second'] : 0
            );
        }

        if (\is_numeric($value)) {
            return DateTime::fromTimestampUnix((int) $value)->time();
        }

        throw new UnexpectedTypeException($value, 'The type of $value should be a DateTimeInterface or null.');
    }
}
