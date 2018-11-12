<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Token;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * An exception thrown when token data is missing.
 */
final class TokenMissingDataKeyException extends AbstractMessageRuntimeException
{
    /**
     * @return TokenMissingDataKeyException
     */
    public static function create(string $key): self
    {
        return new self([
            'key' => $key,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'The authentication token data does not have "{{key}}".',
        ];
    }
}
