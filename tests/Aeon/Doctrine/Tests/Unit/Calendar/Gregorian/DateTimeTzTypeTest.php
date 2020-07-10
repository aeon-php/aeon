<?php

declare(strict_types=1);

namespace Aeon\Doctrine\Tests\Unit\Calendar\Gregorian;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Doctrine\Calendar\Gregorian\DateTimeTzType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

final class DateTimeTzTypeTest extends TestCase
{
    protected function setUp() : void
    {
        if (!Type::hasType(DateTimeTzType::NAME)) {
            Type::addType(DateTimeTzType::NAME, DateTimeTzType::class);
        }
    }

    public function test_converting_valid_values() : void
    {
        $type = Type::getType(DateTimeTzType::NAME);

        $stringDate = $type->convertToDatabaseValue($dateTime = DateTime::fromString('2020-01-01 01:00:00'), $this->createPlatformMock());
        $dateTimeConverted = $type->convertToPHPValue($stringDate, $this->createPlatformMock());

        $this->assertSame('2020-01-01 01:00:00+0000', $stringDate);
        $this->assertEquals($dateTime, $dateTimeConverted);
    }

    public function test_converting_null() : void
    {
        $type = Type::getType(DateTimeTzType::NAME);

        $this->assertNull($type->convertToDatabaseValue(null, $this->createPlatformMock()));
        $this->assertNull($type->convertToPHPValue(null, $this->createPlatformMock()));
    }

    public function test_converting_invalid_value_to_database_value() : void
    {
        $type = Type::getType(DateTimeTzType::NAME);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert PHP value \'invalid date\' of type \'string\' to type \'aeon_datetime_tz\'. Expected one of the following types: null, DateTime');
        $type->convertToDatabaseValue('invalid date', $this->createPlatformMock());
    }

    public function test_converting_invalid_value_to_php_value() : void
    {
        $type = Type::getType(DateTimeTzType::NAME);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert database value "invalid date" to Doctrine Type aeon_datetime_tz. Expected format: Y-m-d H:i:sO');
        $type->convertToPHPValue('invalid date', $this->createPlatformMock());
    }

    /**
     * @return AbstractPlatform
     */
    private function createPlatformMock() : object
    {
        $mock = $this->createMock(AbstractPlatform::class);
        $mock->method('getDateTimeTzFormatString')
            ->willReturn('Y-m-d H:i:sO');

        return $mock;
    }
}
