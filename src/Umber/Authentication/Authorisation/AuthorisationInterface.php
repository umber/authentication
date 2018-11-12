<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation;

/**
 * An authorisation instance aware of roles and permissions.
 */
interface AuthorisationInterface
{
    /**
     * Return the roles the authenticated credentials have access too.
     *
     * @return RoleInterface[]
     */
    public function getRoles(): array;

    /**
     * Check if the authenticated credentials has the given role.
     */
    public function hasRole(string $role): bool;

    /**
     * Return the permissions the authenticated credentials have access too.
     *
     * @return PermissionInterface[]
     */
    public function getPermissions(): array;

    /**
     * Check if the authenticated credentials has the given permission.
     */
    public function hasPermission(string $scope, string $attribute): bool;

    /**
     * Return all the passive permissions given through roles.
     *
     * @return PermissionInterface[]
     */
    public function getPassivePermissions(): array;
}
