<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Authorisation\PermissionInterface;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;

/**
 * {@inheritdoc}
 */
final class PermissionFactory implements PermissionFactoryInterface
{
    private const PERMISSION_REGEX = '/^[^\/]+\:[^\/]+/';

    /**
     * {@inheritdoc}
     *
     * @return Permission
     */
    public function create(string $scope, array $abilities): PermissionInterface
    {
        return new Permission($scope, $abilities);
    }

    /**
     * {@inheritdoc}
     *
     * @return Permission
     */
    public function createFromString(string $permission): PermissionInterface
    {
        if (preg_match(self::PERMISSION_REGEX, $permission) !== 1) {
            throw PermissionSerialisationNameInvalidException::create($permission);
        }

        list($name, $ability) = explode(':', $permission);

        return $this->create($name, [$ability]);
    }
}
