<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver;

use Umber\Authentication\AuthenticationMethodInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticationMethodException;
use Umber\Authentication\Exception\Resolver\UnsupportedAuthenticationMethodException;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * A credentials resolver.
 */
interface CredentialResolverInterface
{
    /**
     * Attempt to resolve all user data for the authentication method provided.
     *
     * @throws CannotResolveAuthenticationMethodException When the resolve fails.
     * @throws UnsupportedAuthenticationMethodException When the authentication method is not supported.
     */
    public function resolve(AuthenticationMethodInterface $method): CredentialInterface;
}
