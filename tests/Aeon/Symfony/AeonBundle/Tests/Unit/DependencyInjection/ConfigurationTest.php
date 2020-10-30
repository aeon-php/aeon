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

        $this->assertEquals('UTC', $config['calendar_timezone']);
        $this->assertEquals('UTC', $config['ui_timezone']);
        $this->assertEquals('Y-m-d H:i:s', $config['ui_datetime_format']);
        $this->assertEquals('Y-m-d', $config['ui_date_format']);
        $this->assertEquals('H:i:s', $config['ui_time_format']);
    }

    public function test_changed_configuration() : void
    {
        $config = $this->process([
            'aeon' => [
                'calendar_timezone' => 'UTC',
                'ui_timezone' => 'America/Los_Angeles',
                'ui_datetime_format' => 'Y-m-d H i s',
                'ui_date_format' => 'Y m d',
                'ui_time_format' => 'H i s',
            ],
        ]);

        $this->assertEquals('UTC', $config['calendar_timezone']);
        $this->assertEquals('America/Los_Angeles', $config['ui_timezone']);
        $this->assertEquals('Y-m-d H i s', $config['ui_datetime_format']);
        $this->assertEquals('Y m d', $config['ui_date_format']);
        $this->assertEquals('H i s', $config['ui_time_format']);
    }

    protected function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
