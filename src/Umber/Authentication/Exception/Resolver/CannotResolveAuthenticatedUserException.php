<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * An exception thrown when the user is not resolved with the credentials.
 */
final class CannotResolveAuthenticatedUserException extends AbstractMessageRuntimeException
{
    /**
     * @return CannotResolveAuthenticatedUserException
     */
    public static function create(): self
    {
        return new self([]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'The authentication resolver did not provide the user.',
        ];
    }
}
