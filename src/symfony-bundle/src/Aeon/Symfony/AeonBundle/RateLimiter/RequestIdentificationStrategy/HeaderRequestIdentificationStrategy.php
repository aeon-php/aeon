<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;

use Aeon\Symfony\AeonBundle\Exception\RequestIdentificationStrategyException;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;
use Symfony\Component\HttpFoundation\Request;

final class HeaderRequestIdentificationStrategy implements RequestIdentificationStrategy
{
    private string $header;

    public function __construct(string $header)
    {
        $this->header = $header;
    }

    public function identify(Request $request) : string
    {
        if ($request->headers->has($this->header)) {
            return (string) $request->headers->get($this->header);
        }

        throw new RequestIdentificationStrategyException("Header \"{$this->header}\" is missing in the request");
    }
}
