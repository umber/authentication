<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a permission is missing abilities.
 */
final class PermissionMissingAbilitiesException extends Exception
{
    /**
     * @return PermissionMissingAbilitiesException
     */
    public static function create(string $scope): self
    {
        $message = implode(' ', [
            'The hierarchy expects that all permissions come with at least one ability.',
            sprintf('The permission scope "%s" has no abilities assigned to it so is considered useless.', $scope),
        ]);

        return new self($message);
    }
}
