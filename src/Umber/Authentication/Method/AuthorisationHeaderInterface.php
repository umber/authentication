<?php

declare(strict_types=1);

namespace Umber\Authentication\Method;

use Umber\Authentication\AuthenticationMethodInterface;

/**
 * An authentication method via authorisation HTTP header.
 *
 * This class implements the basic BEARER scheme for authenticating with credentials.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Authorization
 */
interface AuthorisationHeaderInterface extends AuthenticationMethodInterface
{
    public const TYPE_BEARER = 'bearer';

    /**
     * Return the type.
     */
    public function getType(): string;

    /**
     * Return the credentials.
     */
    public function getCredentials(): string;

    /**
     * Convert back to header string representation.
     */
    public function toString(): string;
}
