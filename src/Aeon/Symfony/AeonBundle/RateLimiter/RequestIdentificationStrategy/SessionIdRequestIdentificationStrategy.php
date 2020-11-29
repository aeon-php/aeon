<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;

use Aeon\Symfony\AeonBundle\Exception\RequestIdentificationStrategyException;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;
use Symfony\Component\HttpFoundation\Request;

final class SessionIdRequestIdentificationStrategy implements RequestIdentificationStrategy
{
    public function identify(Request $request) : string
    {
        if ($request->hasSession()) {
            if (!$request->getSession()->isStarted()) {
                $request->getSession()->start();
            }

            return $request->getSession()->getId();
        }

        throw new RequestIdentificationStrategyException('Session not initialized for request.');
    }
}
