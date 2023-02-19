<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter;

use Aeon\Calendar\TimeUnit;
use Symfony\Component\HttpFoundation\Response;

final class RateLimitHttpProtocol
{
    private int $responseCode;

    private string $responseMessage;

    private string $limit;

    private string $remaining;

    private string $reset;

    public function __construct(int $responseCode, string $responseMessage, string $limit, string $remaining, string $reset)
    {
        $this->responseCode = $responseCode;
        $this->responseMessage = $responseMessage;
        $this->limit = $limit;
        $this->remaining = $remaining;
        $this->reset = $reset;
    }

    public function responseCode() : int
    {
        return $this->responseCode;
    }

    public function responseMessage() : string
    {
        return $this->responseMessage;
    }

    public function limitHeader() : string
    {
        return $this->limit;
    }

    public function remainingHeader() : string
    {
        return $this->remaining;
    }

    public function resetHeader() : string
    {
        return $this->reset;
    }

    public function createHttpResponse(int $limit, int $remaining, TimeUnit $reset) : Response
    {
        $resetSeconds = $reset->microsecond()
            ? $reset->add(TimeUnit::second())->inSeconds()
            : $reset->inSeconds();

        return new Response(
            $this->responseMessage(),
            $this->responseCode(),
            [
                $this->limitHeader() => (string) $limit,
                $this->remainingHeader() => (string) $remaining,
                $this->resetHeader() => (string) $resetSeconds,
            ]
        );
    }

    public function decorateHttpResponse(Response $response, int $limit, int $remaining, TimeUnit $reset) : void
    {
        $resetSeconds = $reset->microsecond()
            ? $reset->add(TimeUnit::second())->inSeconds()
            : $reset->inSeconds();

        $response->headers->set($this->limitHeader(), (string) $limit);
        $response->headers->set($this->remainingHeader(), (string) $remaining);
        $response->headers->set($this->resetHeader(), (string) $resetSeconds);
    }
}
