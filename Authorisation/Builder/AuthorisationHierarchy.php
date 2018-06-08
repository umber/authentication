<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder;

use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactoryInterface;
use Umber\Authentication\Authorisation\Builder\Factory\RoleFactoryInterface;
use Umber\Authentication\Authorisation\Builder\Helper\PermissionMerger;
use Umber\Authentication\Authorisation\PermissionInterface;
use Umber\Authentication\Authorisation\RoleInterface;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;

/**
 * A authorisation hierarchy determining the existence and inheritance of roles and permissions.
 */
final class AuthorisationHierarchy
{
    private $roleFactory;
    private $permissionFactory;

    private $roles = [];
    private $permissions = [];

    public function __construct(
        RoleFactoryInterface $roleFactory,
        PermissionFactoryInterface $permissionFactory
    ) {
        $this->roleFactory = $roleFactory;
        $this->permissionFactory = $permissionFactory;
    }

    /**
     * Add a permission to the hierarchy.
     *
     * @param string[] $abilities
     *
     * @throws DuplicatePermissionScopeException
     * @throws PermissionMissingAbilitiesException
     */
    public function addPermission(string $scope, array $abilities): void
    {
        if ($this->hasPermission($scope)) {
            throw DuplicatePermissionScopeException::create($scope);
        }

        if (count($abilities) === 0) {
            throw PermissionMissingAbilitiesException::create($scope);
        }

        $this->permissions[$scope] = $this->permissionFactory->create($scope, $abilities);
    }

    /**
     * Add a role to the hierarchy.
     *
     * Roles can inherit other roles and contain permissions.
     *
     * @param string[] $roles
     * @param string[] $permissions
     *
     * @throws DuplicateRoleException
     */
    public function addRole(string $name, array $roles, array $permissions): void
    {
        if ($this->hasRole($name)) {
            throw DuplicateRoleException::create($name);
        }

        $availablePermissions = $this->resolvePermissionsByArray($permissions);
        $inheritedPermissions = [];

        foreach ($roles as $role) {
            $instance = $this->getRole($role);

            foreach ($instance->getPassivePermissions() as $passivePermission) {
                $inheritedPermissions[] = $passivePermission;
            }
        }

        $mergedPermissions = array_merge($availablePermissions, $inheritedPermissions);
        $allPermissions = $this->merge($mergedPermissions);

        $this->roles[$name] = $this->roleFactory->create($name, $allPermissions);
    }

    /**
     * Return all the roles.
     *
     * @return RoleInterface[]
     */
    public function getRoles(): array
    {
        return array_values($this->roles);
    }

    /**
     * Check for a role.
     */
    public function hasRole(string $name): bool
    {
        return isset($this->roles[$name]);
    }

    /**
     * Return a role by name.
     *
     * @throws RoleNotFoundException
     */
    public function getRole(string $role): RoleInterface
    {
        if (!isset($this->roles[$role])) {
            throw RoleNotFoundException::create($role);
        }

        return $this->roles[$role];
    }

    /**
     * Return all the permissions.
     *
     * @return PermissionInterface[]
     */
    public function getPermissions(): array
    {
        return array_values($this->permissions);
    }

    /**
     * Check a permission by scope name.
     */
    public function hasPermission(string $scope): bool
    {
        return isset($this->permissions[$scope]);
    }

    /**
     * Return a permission by scope name.
     *
     * @throws PermissionScopeNotFoundException
     */
    public function getPermission(string $scope): PermissionInterface
    {
        if (!isset($this->permissions[$scope])) {
            throw PermissionScopeNotFoundException::create($scope);
        }

        return $this->permissions[$scope];
    }

    /**
     * Return a permission by scope name and ability name.
     *
     * The permission returned is a newly constructed permission using the factory.
     * The permission will contain only the ability provided.
     */
    public function getPermissionAbility(string $scope, string $ability): PermissionInterface
    {
        $permission = $this->getPermission($scope);

        foreach ($permission->getAbilities() as $data) {
            if ($data !== $ability) {
                continue;
            }

            return $this->permissionFactory->create($scope, [$data]);
        }

        throw PermissionAbilityNotFoundException::create($scope, $ability);
    }

    /**
     * Return all the roles by the serialised names.
     *
     * @param string[] $roles
     *
     * @return RoleInterface[]
     */
    public function resolveRolesByArray(array $roles): array
    {
        $resolved = [];

        foreach ($roles as $role) {
            $resolved[] = $this->getRole($role);
        }

        return $resolved;
    }

    /**
     * Return all the permissions by the serialised names.
     *
     * @param string[] $permissions
     *
     * @return PermissionInterface[]
     */
    public function resolvePermissionsByArray(array $permissions): array
    {
        $resolved = [];

        foreach ($permissions as $string) {
            $instance = $this->permissionFactory->createFromString($string);

            $resolved[] = $this->getPermissionAbility(
                $instance->getScope(),
                $instance->getAbilities()[0]
            );
        }

        $resolved = $this->merge($resolved);

        return $resolved;
    }

    /**
     * Merge a permission array and removed duplicates.
     *
     * @param PermissionInterface[] $permissions
     *
     * @return PermissionInterface[]
     */
    private function merge(array $permissions): array
    {
        return PermissionMerger::merge($this->permissionFactory, $permissions);
    }
}
