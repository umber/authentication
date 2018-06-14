<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Role;
use Umber\Authentication\Authorisation\RoleInterface;
use Umber\Authentication\Utility\NameNormaliser;

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
        $role = NameNormaliser::normaliseRoleName($role);

        return new Role($role, array_values($permissions));
    }
}
