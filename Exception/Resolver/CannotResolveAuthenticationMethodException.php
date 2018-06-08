<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * An exception that is thrown when the resolver fails for the authentication method.
 */
final class CannotResolveAuthenticationMethodException extends AbstractRuntimeException
{
    /**
     * @return CannotResolveAuthenticationMethodException
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
            'The authentication method provided did not resolve.',
        ];
    }
}
