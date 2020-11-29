<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter;

use Aeon\RateLimiter\Exception\RuntimeException;
use Aeon\RateLimiter\RateLimiter;

final class RateLimiters
{
    /**
     * @var array<string, RateLimiter>
     */
    private array $rateLimiters;

    public function __construct()
    {
        $this->rateLimiters = [];
    }

    public function add(string $id, RateLimiter $rateLimiter) : void
    {
        $this->rateLimiters[$id] = $rateLimiter;
    }

    public function get(string $id) : RateLimiter
    {
        if (!isset($this->rateLimiters[$id])) {
            throw new RuntimeException(\sprintf('Rate Limiter with id "%s" is not configured', $id));
        }

        return $this->rateLimiters[$id];
    }
}
