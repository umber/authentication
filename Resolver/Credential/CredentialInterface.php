<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\Credential;

/**
 * An object that contains resolved credentials.
 */
interface CredentialInterface
{
    /**
     * Return the serialised authorisation roles.
     *
     * @return string[]
     */
    public function getAuthorisationRoles(): array;

    /**
     * Return the serialised authorisation permissions.
     *
     * @return string[]
     */
    public function getAuthorisationPermissions(): array;
}
