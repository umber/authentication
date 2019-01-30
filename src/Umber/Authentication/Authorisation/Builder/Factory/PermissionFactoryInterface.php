<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\PermissionInterface;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;

/**
 * A permission factory for creating permission instances.
 */
interface PermissionFactoryInterface
{
    /**
     * Create a permission.
     *
     * @param string[] $abilities
     *
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionScopeNameInvalidException
     */
    public function create(string $scope, array $abilities): PermissionInterface;

    /**
     * Create a permission from the serialised form.
     *
     * @throws PermissionSerialisationNameInvalidException
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionScopeNameInvalidException
     */
    public function createFromString(string $permission): PermissionInterface;
}
