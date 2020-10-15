<?php

declare(strict_types=1);

namespace Aeon\AeonBundle\Tests\Unit\DependencyInjection;

use Aeon\AeonBundle\DependencyInjection\AeonExtension;
use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Twig\CalendarExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;

final class AeonExtensionTest extends TestCase
{
    private ?KernelInterface $kernel;

    private ?ContainerBuilder $container;

    protected function setUp() : void
    {
        parent::setUp();

        $this->kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $this->container = new ContainerBuilder();
    }

    protected function tearDown() : void
    {
        parent::tearDown();

        $this->container = null;
        $this->kernel = null;
    }

    public function test_default_configuration() : void
    {
        $extension = new AeonExtension();
        $extension->load(
            [
                [],
            ],
            $this->container
        );

        $this->assertTrue($this->container->hasDefinition('calendar'));
        $this->assertInstanceOf(Calendar::class, $this->container->get('calendar'));
        $this->assertTrue($this->container->hasDefinition('calendar.twig'));
        $this->assertInstanceOf(CalendarExtension::class, $this->container->get('calendar.twig'));
    }
}
