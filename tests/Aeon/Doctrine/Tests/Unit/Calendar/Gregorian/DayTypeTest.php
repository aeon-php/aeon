<?php

declare(strict_types=1);

namespace Aeon\Doctrine\Tests\Unit\Calendar\Gregorian;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Doctrine\Calendar\Gregorian\DayType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class DayTypeTest extends TestCase
{
    protected function setUp() : void
    {
        if (!Type::hasType(DayType::NAME)) {
            Type::addType(DayType::NAME, DayType::class);
        }
    }

    public function test_converting_valid_values() : void
    {
        $type = Type::getType(DayType::NAME);

        $stringDate = $type->convertToDatabaseValue($dateTime = Day::fromString('2020-01-01'), $this->createPlatformMock());
        $dateTimeConverted = $type->convertToPHPValue($stringDate, $this->createPlatformMock());

        $this->assertSame('2020-01-01', $stringDate);
        $this->assertEquals($dateTime, $dateTimeConverted);
    }

    public function test_converting_null() : void
    {
        $type = Type::getType(DayType::NAME);

        $this->assertNull($type->convertToDatabaseValue(null, $this->createPlatformMock()));
        $this->assertNull($type->convertToPHPValue(null, $this->createPlatformMock()));
    }

    public function test_converting_invalid_value_to_database_value() : void
    {
        $type = Type::getType(DayType::NAME);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert PHP value \'invalid date\' of type \'string\' to type \'aeon_day\'. Expected one of the following types: null, Day');
        $type->convertToDatabaseValue('invalid date', $this->createPlatformMock());
    }

    public function test_converting_invalid_value_to_php_value() : void
    {
        $type = Type::getType(DayType::NAME);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert database value "invalid date" to Doctrine Type aeon_day. Expected format: Y-m-d');
        $type->convertToPHPValue('invalid date', $this->createPlatformMock());
    }

    /**
     * @return AbstractPlatform
     */
    private function createPlatformMock() : object
    {
        $mock = $this->createMock(AbstractPlatform::class);
        $mock->method('getDateFormatString')
            ->willReturn('Y-m-d');

        return $mock;
    }
}
