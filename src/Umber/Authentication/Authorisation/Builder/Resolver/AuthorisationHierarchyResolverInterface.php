<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Resolver;

use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;

interface AuthorisationHierarchyResolverInterface
{
    /**
     * Resolve the hierarchy.
     *
     * @throws DuplicateRoleException
     * @throws DuplicatePermissionScopeException
     * @throws PermissionMissingAbilitiesException
     */
    public function resolve(): AuthorisationHierarchy;
}
