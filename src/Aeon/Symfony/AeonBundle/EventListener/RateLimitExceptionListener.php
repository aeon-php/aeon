<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\EventListener;

use Aeon\RateLimiter\Exception\RateLimitException;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimitHttpProtocol;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class RateLimitExceptionListener
{
    private RateLimitHttpProtocol $rateLimitHttpProtocol;

    public function __construct(RateLimitHttpProtocol $rateLimitHttpProtocol)
    {
        $this->rateLimitHttpProtocol = $rateLimitHttpProtocol;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof RateLimitException) {
            $event->setResponse($this->rateLimitHttpProtocol->createHttpResponse(
                $exception->limit(),
                0,
                $exception->retryIn()
            ));
        }
    }
}
