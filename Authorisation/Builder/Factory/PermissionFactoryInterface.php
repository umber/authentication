<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\PermissionInterface;

/**
 * A permission factory for creating permission instances.
 */
interface PermissionFactoryInterface
{
    /**
     * Create a permission.
     *
     * @param string[] $abilities
     */
    public function create(string $scope, array $abilities): PermissionInterface;

    /**
     * Create a permission from the serialised form.
     */
    public function createFromString(string $permission): PermissionInterface;
}
