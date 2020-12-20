<?php

declare(strict_types=1);

namespace Aeon\Calendar\Tests\Functional\Holidays;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Holidays\Providers;
use PHPUnit\Framework\TestCase;
use Yasumi\Provider\USA;

final class ProvidersTest extends TestCase
{
    public function test_getting_provider_by_country_code() : void
    {
        $this->assertSame(Providers::fromCountryCode('US'), USA::class);
    }

    public function test_getting_provider_by_country_code_that_does_not_exists() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Country code USA is ont assigned to any Yasumi provider.');

        $this->assertSame(Providers::fromCountryCode('USA'), USA::class);
    }
}
