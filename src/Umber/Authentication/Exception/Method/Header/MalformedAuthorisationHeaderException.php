<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Method\Header;

use Exception;

/**
 * An exception that is thrown when the header is not formatted correctly.
 */
final class MalformedAuthorisationHeaderException extends Exception
{
    /**
     * @return MalformedAuthorisationHeaderException
     */
    public static function create(string $header): self
    {
        return new self($header);
    }

    public function __construct(string $header)
    {
        $message = 'The authentication header "%s" is malformed.';
        $message = sprintf($message, $header);

        parent::__construct($message);
    }
}
