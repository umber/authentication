<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;

/**
 * An exception that is thrown when the resolver fails for the authentication method.
 */
final class CannotResolveAuthenticationMethodException extends Exception
{
    /**
     * @return CannotResolveAuthenticationMethodException
     */
    public static function create(): self
    {
        $message = 'The authentication method provided did not resolve.';

        return new self($message);
    }
}
