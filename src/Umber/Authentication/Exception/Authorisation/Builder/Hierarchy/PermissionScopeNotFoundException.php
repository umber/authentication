<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a permission scope not found.
 */
final class PermissionScopeNotFoundException extends Exception
{
    /**
     * @return PermissionScopeNotFoundException
     */
    public static function create(string $scope): self
    {
        return new self($scope);
    }

    public function __construct(string $scope)
    {
        $message = 'The permission scope "%s" was not found.';
        $message = sprintf($message, $scope);

        parent::__construct($message);
    }
}
