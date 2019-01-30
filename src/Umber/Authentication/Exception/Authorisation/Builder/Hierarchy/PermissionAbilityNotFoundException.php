<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a permission ability is not found.
 */
final class PermissionAbilityNotFoundException extends Exception
{
    /**
     * @return PermissionAbilityNotFoundException
     */
    public static function create(string $scope, string $ability): self
    {
        $message = 'The permission ability "%s" was not found against the scope "%s".';
        $message = sprintf($message, $ability, $scope);

        return new self($message);
    }
}
