<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;
use Throwable;

/**
 * An exception that is thrown when the resolver fails for the authentication method.
 */
final class CannotResolveAuthenticationMethodException extends Exception
{
    /**
     * @return CannotResolveAuthenticationMethodException
     */
    public static function create(?Throwable $previous = null): self
    {
        return new self($previous);
    }

    public function __construct(?Throwable $previous = null)
    {
        $message = 'The authentication method provided did not resolve.';

        parent::__construct($message, 0, $previous);
    }
}
