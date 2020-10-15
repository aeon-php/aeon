<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\DependencyInjection;

use Aeon\Symfony\AeonBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function test_default_configuration() : void
    {
        $config = $this->process([]);

        $this->assertEquals('UTC', $config['timezone']);
        $this->assertEquals('Y-m-d H:i:s', $config['datetime_format']);
        $this->assertEquals('Y-m-d', $config['date_format']);
        $this->assertEquals('H:i:s', $config['time_format']);
    }

    protected function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
