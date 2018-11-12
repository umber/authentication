<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * An exception that is thrown when a user resolver does not support the authentication method.
 */
final class UnsupportedAuthenticationMethodException extends AbstractMessageRuntimeException
{
    /**
     * @return UnsupportedAuthenticationMethodException
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
            'The authentication method is not supported.',
        ];
    }
}
