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
        return new self($scope, $ability);
    }

    public function __construct(string $scope, string $ability)
    {
        $message = implode(' ', [
            'A permission ability should only contain alphabetic characters and hyphens or underscores.',
            'The permission ability "%s" is invalid for scope "%s".',
        ]);

        $message = sprintf($message, $ability, $scope);

        parent::__construct($message);
    }
}
