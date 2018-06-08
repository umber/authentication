<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * An exception that is thrown when a user resolver does not support the authentication method.
 */
final class UnsupportedAuthenticationMethodException extends AbstractRuntimeException
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
    public static function getMessageTemplate(): array
    {
        return [
            'The authentication method is not supported.',
        ];
    }
}
