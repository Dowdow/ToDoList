<?php

namespace LeoAndLeo\GoogleBundle\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

class Authenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface {

    /**
     * {@inheritDoc}
     */
    public function createToken(Request $request, $providerKey)
    {
        return new PreAuthenticatedToken(
            'anon.',
            null,
            $providerKey
        );
    }

    /**
     * {@inheritDoc}
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        return new PreAuthenticatedToken(
            'anon.',
            null,
            $providerKey,
            []
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication Failed.", 403);
    }
}