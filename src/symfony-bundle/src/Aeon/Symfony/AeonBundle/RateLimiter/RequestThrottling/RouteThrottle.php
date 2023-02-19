<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling;

use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\RateLimiter;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;
use Symfony\Component\HttpFoundation\Request;

final class RouteThrottle
{
    private string $name;

    private RateLimiter $rateLimiter;

    /**
     * @var array<string>
     */
    private array $methods;

    private RequestIdentificationStrategy $requestIdentificationStrategy;

    /**
     * @param array<string> $methods
     */
    public function __construct(string $name, RateLimiter $rateLimiter, array $methods, RequestIdentificationStrategy $requestIdentificationStrategy)
    {
        $this->name = $name;
        $this->rateLimiter = $rateLimiter;
        $this->methods = \array_map(fn (string $method) : string => \strtoupper($method), $methods);
        $this->requestIdentificationStrategy = $requestIdentificationStrategy;
    }

    public function is(string $name, string $method) : bool
    {
        if (\strtolower($this->name) !== \strtolower($name)) {
            return false;
        }

        if (!\count($this->methods)) {
            return true;
        }

        return \in_array($method, $this->methods, true);
    }

    public function limit() : int
    {
        return $this->rateLimiter->capacityInitial();
    }

    public function resetIn(Request $request) : TimeUnit
    {
        return $this->rateLimiter->resetIn($this->requestIdentificationStrategy->identify($request));
    }

    public function capacity(Request $request) : int
    {
        return $this->rateLimiter->capacity($this->requestIdentificationStrategy->identify($request));
    }

    public function estimate(Request $request) : TimeUnit
    {
        return $this->rateLimiter->estimate($this->requestIdentificationStrategy->identify($request));
    }

    /**
     * @throws \Aeon\RateLimiter\Exception\RateLimitException
     */
    public function hit(Request $request) : void
    {
        $this->rateLimiter->hit($this->requestIdentificationStrategy->identify($request));
    }
}
