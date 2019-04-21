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
        return new self($scope);
    }

    public function __construct(string $scope)
    {
        $message = implode(' ', [
            'A permission scope name should only contain alphabetic characters and hyphens or underscores.',
            'The permission scope name provided "%s" is invalid.',
        ]);

        $message = sprintf($message, $scope);

        parent::__construct($message);
    }
}
