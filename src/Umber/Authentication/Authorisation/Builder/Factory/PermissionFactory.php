<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Factory;

use Umber\Authentication\Authorisation\Permission;
use Umber\Authentication\Authorisation\PermissionInterface;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Utility\NameNormaliser;

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
        $scope = NameNormaliser::normalisePermissionScope($scope);

        $abilities = array_map(static function ($value) {
            return NameNormaliser::normalisePermissionAbility($value);
        }, $abilities);

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

        [$name, $ability] = explode(':', $permission);

        return $this->create($name, [$ability]);
    }
}
