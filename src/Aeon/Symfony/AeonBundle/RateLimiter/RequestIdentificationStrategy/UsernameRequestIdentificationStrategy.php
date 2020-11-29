<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;

use Aeon\Symfony\AeonBundle\Exception\RequestIdentificationStrategyException;
use Aeon\Symfony\AeonBundle\RateLimiter\RequestIdentificationStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UsernameRequestIdentificationStrategy implements RequestIdentificationStrategy
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function identify(Request $request) : string
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            throw new RequestIdentificationStrategyException('Request not authorized.');
        }

        $user = $token->getUser();

        if (\is_string($user)) {
            return $user;
        }

        if ($user instanceof \Stringable) {
            return (string) $user;
        }

        return $user->getUsername();
    }
}
