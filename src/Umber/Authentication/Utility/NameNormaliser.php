<?php

declare(strict_types=1);

namespace Umber\Authentication\Utility;

/**
 * A name normaliser for use within authentication components.
 */
final class NameNormaliser
{
    /**
     * Normalise the role name.
     */
    public static function normaliseRoleName(string $role): string
    {
        return strtolower($role);
    }

    /**
     * Normalise the permission scope.
     */
    public static function normalisePermissionScope(string $scope): string
    {
        return strtolower($scope);
    }

    /**
     * Normalise the permission ability.
     */
    public static function normalisePermissionAbility(string $ability): string
    {
        return strtolower($ability);
    }
}
