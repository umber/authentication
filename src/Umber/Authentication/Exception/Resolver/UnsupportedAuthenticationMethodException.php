<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;

/**
 * An exception that is thrown when a user resolver does not support the authentication method.
 */
final class UnsupportedAuthenticationMethodException extends Exception
{
    /**
     * @return UnsupportedAuthenticationMethodException
     */
    public static function create(): self
    {
        $message = 'The authentication method is not supported.';

        return new self($message);
    }
}
