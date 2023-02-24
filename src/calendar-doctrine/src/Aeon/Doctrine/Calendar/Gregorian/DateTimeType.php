<?php

declare(strict_types=1);

namespace Aeon\Doctrine\Calendar\Gregorian;

use Aeon\Calendar\Gregorian\DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

final class DateTimeType extends DateTimeImmutableType
{
    public const NAME = 'aeon_datetime';

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-ignore-next-line
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTime || $value instanceof \DateTimeInterface) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime', '\DateTimeInterface']);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?DateTime
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        if (!\is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string']);
        }

        try {
            $val = DateTime::fromString($value);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString(), $e);
        }

        return $val;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
