<?php

namespace Umber\Authentication\Prototype\Authorisation;

/**
 * An interface with core requirements for authentication.
 */
interface AuthorisationAwareInterface
{
    /**
     * Return the serialised user authorisation roles.
     *
     * @return string[]
     */
    public function getAuthorisationRoles(): array;

    /**
     * Return the serialised user authorisation permissions.
     *
     * @return string[]
     */
    public function getAuthorisationPermissions(): array;
}
