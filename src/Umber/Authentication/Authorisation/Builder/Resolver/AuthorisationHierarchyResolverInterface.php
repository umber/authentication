<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Resolver;

use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;

interface AuthorisationHierarchyResolverInterface
{
    /**
     * Resolve the hierarchy.
     *
     * @throws DuplicateRoleException
     * @throws RoleNotFoundException
     * @throws RoleNameInvalidException
     *
     * @throws DuplicatePermissionScopeException
     * @throws PermissionScopeNotFoundException
     * @throws PermissionScopeNameInvalidException
     * @throws PermissionSerialisationNameInvalidException
     * @throws PermissionAbilityNotFoundException
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionMissingAbilitiesException
     */
    public function resolve(): AuthorisationHierarchy;
}
