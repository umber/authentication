<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver;

use Umber\Authentication\AuthenticationMethodInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedUserException;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticationMethodException;
use Umber\Authentication\Exception\Resolver\UnsupportedAuthenticationMethodException;
use Umber\Authentication\Exception\Token\TokenExpiredException;
use Umber\Authentication\Exception\Token\TokenNotVerifiedException;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * A credentials resolver.
 */
interface CredentialResolverInterface
{
    /**
     * Attempt to resolve all user data for the authentication method provided.
     *
     * @throws TokenExpiredException
     * @throws TokenNotVerifiedException
     *
     * @throws CannotResolveAuthenticationMethodException
     * @throws CannotResolveAuthenticatedUserException
     * @throws UnsupportedAuthenticationMethodException
     */
    public function resolve(AuthenticationMethodInterface $method): CredentialInterface;
}
