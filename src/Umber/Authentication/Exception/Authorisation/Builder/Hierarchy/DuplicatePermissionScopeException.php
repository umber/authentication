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
        return new self($scope);
    }

    public function __construct(string $scope)
    {
        $message = implode(' ', [
            'The hierarchy cannot contain duplicate permission scopes.',
            'The permission scope "%s" has already been defined and cannot be overwritten or merged.',
        ]);

        $message = sprintf($message, $scope);

        parent::__construct($message);
    }
}
