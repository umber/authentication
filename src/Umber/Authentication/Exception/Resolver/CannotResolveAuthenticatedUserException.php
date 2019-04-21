<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Exception;

/**
 * An exception thrown when the user is not resolved with the credentials.
 */
final class CannotResolveAuthenticatedUserException extends Exception
{
    /**
     * @return CannotResolveAuthenticatedUserException
     */
    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $message = 'The authentication resolver did not provide the user.';

        parent::__construct($message);
    }
}
