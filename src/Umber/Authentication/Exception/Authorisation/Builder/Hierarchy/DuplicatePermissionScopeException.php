<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a permission scope is duplicated.
 */
final class DuplicatePermissionScopeException extends Exception
{
    /**
     * @return DuplicatePermissionScopeException
     */
    public static function create(string $scope): self
    {
        $message = implode(' ', [
            'The hierarchy cannot contain duplicate permission scopes.',
            sprintf('The permission scope "%s" has already been defined and cannot be overwritten or merged.', $scope),
        ]);

        return new self($message);
    }
}
