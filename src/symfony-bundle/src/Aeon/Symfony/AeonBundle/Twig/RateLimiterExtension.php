<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Twig;

use Aeon\Calendar\TimeUnit;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimiters;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RateLimiterExtension extends AbstractExtension
{
    /**
     * @var RateLimiters
     */
    private RateLimiters $rateLimiters;

    public function __construct(RateLimiters $rateLimiters)
    {
        $this->rateLimiters = $rateLimiters;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('aeon_is_throttled', [$this, 'is_throttled']),
            new TwigFunction('aeon_until_throttled', [$this, 'until_throttled']),
            new TwigFunction('aeon_throttle_expire', [$this, 'throttle_expire']),
        ];
    }

    public function is_throttled(string $rateLimiterId, string $id) : bool
    {
        return $this->rateLimiters->get($rateLimiterId)->capacity($id) === 0;
    }

    public function until_throttled(string $rateLimiterId, string $id) : int
    {
        return $this->rateLimiters->get($rateLimiterId)->capacity($id);
    }

    public function throttle_expire(string $rateLimiterId, string $id) : TimeUnit
    {
        return $this->rateLimiters->get($rateLimiterId)->estimate($id);
    }
}
