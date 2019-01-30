<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Role;

use Exception;

/**
 * An exception thrown when a role name is invalid.
 */
final class RoleNameInvalidException extends Exception
{
    /**
     * @return RoleNameInvalidException
     */
    public static function create(string $role): self
    {
        $message = implode(' ', [
            'A role name should only contain alphabetic characters and hyphens or underscores.',
            sprintf('The role name "%s" is invalid.', $role),
        ]);

        return new self($message);
    }
}
