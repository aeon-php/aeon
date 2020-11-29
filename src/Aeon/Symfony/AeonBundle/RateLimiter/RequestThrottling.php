<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter;

use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling\RouteThrottle;
use Symfony\Component\HttpFoundation\Request;

final class RequestThrottling
{
    /**
     * @var RouteThrottle[]
     */
    private array $routes;

    /**
     * @param array<RouteThrottle> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function findFor(Request $request) : ?RouteThrottle
    {
        /** @var null|string $requestName */
        $requestName = $request->attributes->get('_route');

        if (!\is_string($requestName)) {
            return null;
        }

        foreach ($this->routes as $route) {
            if ($route->is($requestName, $request->getMethod())) {
                return $route;
            }
        }

        return null;
    }
}
