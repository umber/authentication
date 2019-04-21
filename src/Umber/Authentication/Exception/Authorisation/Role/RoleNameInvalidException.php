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
        return new self($role);
    }

    public function __construct(string $role)
    {
        $message = implode(' ', [
            'A role name should only contain alphabetic characters and hyphens or underscores.',
            'The role name "%s" is invalid.',
        ]);

        $message = sprintf($message, $role);

        parent::__construct($message);
    }
}
