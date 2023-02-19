<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional\App;

use Symfony\Component\Routing\RouteCollectionBuilder;

final class TestAppRouteCollectionKernel extends TestAppKernel
{
    protected function configureRoutes(RouteCollectionBuilder $routes) : void
    {
        $routes->add('/holiday', 'kernel::holiday', 'holiday');
        $routes->add('/throttle', 'kernel::throttle', 'throttle');
        $routes->add('/manual-throttle', 'kernel::manualThrottle', 'manual-throttle');
    }
}
