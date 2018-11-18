<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Resolver;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * An exception that is thrown when the resolver fails for the authentication method.
 */
final class CannotResolveAuthenticationMethodException extends AbstractMessageRuntimeException
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
    public static function message(): array
    {
        return [
            'The authentication method provided did not resolve.',
        ];
    }
}
