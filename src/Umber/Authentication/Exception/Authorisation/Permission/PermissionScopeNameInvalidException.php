<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Exception;

/**
 * An exception thrown when a permission scope is invalid.
 */
final class PermissionScopeNameInvalidException extends Exception
{
    /**
     * @return PermissionScopeNameInvalidException
     */
    public static function create(string $scope): self
    {
        $message = implode(' ', [
            'A permission scope name should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The permission scope name provided "%s" is invalid.', $scope),
        ]);

        return new self($message);
    }
}
