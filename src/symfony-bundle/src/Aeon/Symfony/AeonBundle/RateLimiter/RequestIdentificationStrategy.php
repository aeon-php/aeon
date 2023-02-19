<?php declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter;

use Symfony\Component\HttpFoundation\Request;

interface RequestIdentificationStrategy
{
    public function identify(Request $request) : string;
}
