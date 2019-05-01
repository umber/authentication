<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;
use Throwable;

/**
 * An exception thrown when the resolve cannot provide credentials.
 */
final class CannotResolveAuthenticatedCredentialException extends Exception
{
    /**
     * @return CannotResolveAuthenticatedCredentialException
     */
    public static function create(?Throwable $previous = null): self
    {
        return new self($previous);
    }

    public function __construct(?Throwable $previous = null)
    {
        $message = 'The authentication resolver did not provide credentials.';

        parent::__construct($message, 0, $previous);
    }
}
