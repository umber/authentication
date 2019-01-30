<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\PermissionInterface;
use Umber\Authentication\Authorisation\RoleInterface;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;

/**
 * A role factory for creating role instances.
 */
interface RoleFactoryInterface
{
    /**
     * Create a role instance.
     *
     * @param PermissionInterface[] $permissions
     *
     * @throws RoleNameInvalidException
     */
    public function create(string $role, array $permissions): RoleInterface;
}
