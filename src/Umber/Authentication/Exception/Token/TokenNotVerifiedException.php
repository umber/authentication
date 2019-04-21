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
        return new self($previous);
    }

    public function __construct(?Throwable $previous = null)
    {
        $message = 'The token provided cannot be verified.';

        parent::__construct($message, 0, $previous);
    }
}
