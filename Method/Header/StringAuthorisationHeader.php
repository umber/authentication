<?php

declare(strict_types=1);

namespace Umber\Authentication\Method\Header;

use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Method\AuthorisationHeaderInterface;

/**
 * An implementation of authorisation header that accepts the string representation.
 */
final class StringAuthorisationHeader implements AuthorisationHeaderInterface
{
    /** @var AuthorisationHeader */
    private $header;

    /**
     * @throws MalformedAuthorisationHeaderException When the authorisation header is malformed.
     */
    public function __construct(string $string)
    {
        $parts = explode(' ', $string, 2);

        if (count($parts) === 1) {
            throw MalformedAuthorisationHeaderException::create($string);
        }

        $this->header = new AuthorisationHeader(
            $parts[0],
            $parts[1]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->header->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): string
    {
        return $this->header->getCredentials();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->header->toString();
    }

    /**
     * Magic conversion to string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
