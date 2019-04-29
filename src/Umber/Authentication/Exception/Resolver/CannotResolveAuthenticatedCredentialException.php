<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;

/**
 * An exception thrown when the resolve cannot provide credentials.
 */
final class CannotResolveAuthenticatedCredentialException extends Exception
{
    /**
     * @return CannotResolveAuthenticatedCredentialException
     */
    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $message = 'The authentication resolver did not provide credentials.';

        parent::__construct($message);
    }
}
