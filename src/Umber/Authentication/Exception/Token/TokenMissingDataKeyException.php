<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Token;

use Exception;

/**
 * An exception thrown when token data is missing.
 */
final class TokenMissingDataKeyException extends Exception
{
    /**
     * @return TokenMissingDataKeyException
     */
    public static function create(string $key): self
    {
        return new self($key);
    }

    public function __construct(string $key)
    {
        $message = 'The authentication token does not have data key "%s".';
        $message = sprintf($message, $key);

        parent::__construct($message);
    }
}
