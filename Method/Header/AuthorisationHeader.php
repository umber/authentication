<?php

declare(strict_types=1);

namespace Umber\Authentication\Method\Header;

use Umber\Authentication\Method\AuthorisationHeaderInterface;

/**
 * The authorisation header.
 *
 * Accepts an unrestricted type and credentials.
 */
final class AuthorisationHeader implements AuthorisationHeaderInterface
{
    private $type;
    private $credentials;

    public function __construct(string $type, string $credentials)
    {
        $this->type = strtolower($type);
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('%s %s', ucwords($this->type), $this->credentials);
    }

    /**
     * Magic conversion to string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
