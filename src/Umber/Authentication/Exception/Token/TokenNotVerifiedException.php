<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Token;

use Exception;
use Throwable;

/**
 * An exception thrown when the token cannot be verified.
 */
final class TokenNotVerifiedException extends Exception
{
    /**
     * @return TokenNotVerifiedException
     */
    public static function create(?Throwable $previous = null): self
    {
        $message = 'The token provided cannot be verified.';

        return new self($message, null, $previous);
    }
}
