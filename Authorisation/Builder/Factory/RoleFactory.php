<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Role;
use Umber\Authentication\Authorisation\RoleInterface;

/**
 * {@inheritdoc}
 */
final class RoleFactory implements RoleFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Role
     */
    public function create(string $role, array $permissions): RoleInterface
    {
        return new Role($role, array_values($permissions));
    }
}
