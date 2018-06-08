<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Helper;

use Umber\Authentication\Authorisation\Builder\Factory\PermissionFactoryInterface;
use Umber\Authentication\Authorisation\PermissionInterface;

/**
 * A permission helper for merging and removing duplicates.
 */
final class PermissionMerger
{
    /**
     * Merge and remove duplicates (unique) the given permissions array.
     *
     * Permissions are destroyed and replaced with new permissions using the given factory.
     * So assume the returned array contains an entirely new set of permissions in 99% of the cases.
     *
     * @param PermissionInterface[] $permissions
     *
     * @return PermissionInterface[]
     */
    public static function merge(PermissionFactoryInterface $factory, array $permissions): array
    {
        /** @var PermissionInterface[] $merged */
        $merged = [];

        foreach ($permissions as $permission) {
            if (!isset($merged[$permission->getScope()])) {
                $merged[$permission->getScope()] = $permission;

                continue;
            }

            $previous = $merged[$permission->getScope()];

            $abilities = array_values(
                array_unique(
                    array_merge(
                        $previous->getAbilities(),
                        $permission->getAbilities()
                    )
                )
            );

            sort($abilities);

            $merged[$permission->getScope()] = $factory->create(
                $permission->getScope(),
                $abilities
            );
        }

        ksort($merged);

        return array_values($merged);
    }
}
