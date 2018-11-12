<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Method\Header;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * An exception that is thrown when the header is not formatted correctly.
 */
final class MalformedAuthorisationHeaderException extends AbstractMessageRuntimeException
{
    /**
     * @return MalformedAuthorisationHeaderException
     */
    public static function create(string $string): self
    {
        return new self([
            'string' => $string,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'The authentication header "{{ string }}" is malformed.',
            'The expected representation is "TYPE CREDENTIALS", that is a type followed by a space followed by the credentials.',
        ];
    }
}
