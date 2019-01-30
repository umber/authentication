<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Exception;

/**
 * An exception thrown when a permission ability is invalid.
 */
final class PermissionAbilityNameInvalidException extends Exception
{
    /**
     * @return PermissionAbilityNameInvalidException
     */
    public static function create(string $scope, string $ability): self
    {
        $message = implode(' ', [
            'A permission ability should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The permission ability "%s" is invalid for scope "%s".', $ability, $scope),
        ]);

        return new self($message);
    }
}
