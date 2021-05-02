<?php

declare(strict_types=1);

namespace Aeon\Doctrine\Tests\Unit;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Doctrine\Calendar\Gregorian\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class DateTimeTypeTest extends TestCase
{
    protected function setUp() : void
    {
        if (!Type::hasType(DateTimeType::NAME)) {
            Type::addType(DateTimeType::NAME, DateTimeType::class);
        }
    }

    public function test_converting_valid_values() : void
    {
        $type = Type::getType(DateTimeType::NAME);

        $stringDate = $type->convertToDatabaseValue($dateTime = DateTime::fromString('2020-01-01 01:00:00'), $this->createPlatformMock());
        $dateTimeConverted = $type->convertToPHPValue($stringDate, $this->createPlatformMock());

        $this->assertSame('2020-01-01 01:00:00', $stringDate);
        $this->assertObjectEquals($dateTime, $dateTimeConverted, 'isEqual');
    }

    public function test_converting_null() : void
    {
        $type = Type::getType(DateTimeType::NAME);

        $this->assertNull($type->convertToDatabaseValue(null, $this->createPlatformMock()));
        $this->assertNull($type->convertToPHPValue(null, $this->createPlatformMock()));
    }

    public function test_converting_invalid_value_to_database_value() : void
    {
        $type = Type::getType(DateTimeType::NAME);

        $this->expectException(ConversionException::class);
        $type->convertToDatabaseValue('invalid date', $this->createPlatformMock());
    }

    public function test_converting_invalid_value_to_php_value() : void
    {
        $type = Type::getType(DateTimeType::NAME);

        $this->expectException(ConversionException::class);
        $type->convertToPHPValue('invalid date', $this->createPlatformMock());
    }

    /**
     * @return AbstractPlatform
     */
    private function createPlatformMock() : object
    {
        $mock = $this->createMock(AbstractPlatform::class);
        $mock->method('getDateTimeFormatString')
            ->willReturn('Y-m-d H:i:s');

        return $mock;
    }
}
