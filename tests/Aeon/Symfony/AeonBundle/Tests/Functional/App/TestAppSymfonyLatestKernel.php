<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional\App;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class TestAppSymfonyLatestKernel extends TestAppKernel
{
    protected function configureRoutes(RoutingConfigurator $routes) : void
    {
        $routes->add('holiday', '/holiday')->controller([$this, 'holiday']);
        $routes->add('throttle', '/throttle')->controller([$this, 'throttle']);
        $routes->add('manual-throttle', '/manual-throttle')->controller([$this, 'manualThrottle']);
    }
}
