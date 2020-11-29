<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\EventListener;

use Aeon\Calendar\TimeUnit;
use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\Symfony\AeonBundle\Exception\RequestIdentificationStrategyException;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimitHttpProtocol;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestThrottling;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RateLimitRequestListener
{
    private RequestThrottling $requestThrottling;

    private RateLimitHttpProtocol $rateLimitHttpProtocol;

    public function __construct(RateLimitHttpProtocol $rateLimitHttpProtocol, RequestThrottling $requestThrottling)
    {
        $this->requestThrottling = $requestThrottling;
        $this->rateLimitHttpProtocol = $rateLimitHttpProtocol;
    }

    public function onKernelRequest(RequestEvent $event) : void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $throttle = $this->requestThrottling->findFor($event->getRequest());

        if ($throttle === null) {
            return;
        }

        try {
            $throttle->hit($event->getRequest());
        } catch (RateLimitException $rateLimitException) {
            $event->setResponse($this->rateLimitHttpProtocol->createHttpResponse(
                $rateLimitException->limit(),
                0,
                $rateLimitException->retryIn()
            ));
        } catch (RequestIdentificationStrategyException $identificationStrategyException) {
            $event->setResponse($this->rateLimitHttpProtocol->createHttpResponse(
                $throttle->limit(),
                0,
                TimeUnit::seconds(0)
            ));
        }
    }
}
