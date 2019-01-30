<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation;

use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Utility\NameNormaliser;

/**
 * {@inheritdoc}
 */
final class Authorisation implements AuthorisationInterface
{
    /** @var RoleInterface[] */
    private $roles;

    /** @var PermissionInterface[] */
    private $permissions;

    /** @var PermissionInterface[] */
    private $passivePermissions = [];

    /**
     * @param string[] $roles
     * @param string[] $permissions
     *
     * @throws RoleNotFoundException
     * @throws PermissionAbilityNotFoundException
     * @throws PermissionScopeNotFoundException
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionScopeNameInvalidException
     * @throws PermissionSerialisationNameInvalidException
     */
    public function __construct(array $roles, array $permissions, AuthorisationHierarchy $hierarchy)
    {
        $this->roles = $hierarchy->resolveRolesByArray($roles);
        $this->permissions = $hierarchy->resolvePermissionsByArray($permissions);

        foreach ($this->roles as $role) {
            foreach ($role->getPassivePermissions() as $permission) {
                $this->passivePermissions[] = $permission;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole(string $role): bool
    {
        $role = NameNormaliser::normaliseRoleName($role);

        foreach ($this->roles as $instance) {
            if (NameNormaliser::normaliseRoleName($instance->getName()) === $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission(string $scope, string $ability): bool
    {
        $scope = NameNormaliser::normalisePermissionScope($scope);

        $found = null;

        foreach ($this->permissions as $permission) {
            if (NameNormaliser::normalisePermissionScope($permission->getScope()) === $scope) {
                $found = $permission;

                break;
            }
        }

        if ($found === null) {
            foreach ($this->passivePermissions as $permission) {
                if (NameNormaliser::normalisePermissionScope($permission->getScope()) === $scope) {
                    $found = $permission;

                    break;
                }
            }
        }

        if ($found === null) {
            return false;
        }

        return $found->hasAbility($ability);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassivePermissions(): array
    {
        return $this->passivePermissions;
    }
}
