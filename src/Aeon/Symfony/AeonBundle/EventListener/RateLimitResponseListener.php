<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\EventListener;

use Aeon\Symfony\AeonBundle\RateLimiter\RateLimitHttpProtocol;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class RateLimitResponseListener
{
    private RequestThrottling $requestThrottling;

    private RateLimitHttpProtocol $rateLimitHttpProtocol;

    public function __construct(RateLimitHttpProtocol $rateLimitHttpProtocol, RequestThrottling $requestThrottling)
    {
        $this->requestThrottling = $requestThrottling;
        $this->rateLimitHttpProtocol = $rateLimitHttpProtocol;
    }

    public function onKernelResponse(ResponseEvent $event) : void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($event->getResponse()->getStatusCode() === $this->rateLimitHttpProtocol->responseCode()) {
            return;
        }

        $throttle = $this->requestThrottling->findFor($event->getRequest());

        if ($throttle === null) {
            return;
        }

        $this->rateLimitHttpProtocol->decorateHttpResponse(
            $event->getResponse(),
            $throttle->limit(),
            $throttle->capacity($event->getRequest()),
            $throttle->resetIn($event->getRequest())
        );
    }
}
