<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a role is duplicated.
 */
final class DuplicateRoleException extends Exception
{
    /**
     * @return DuplicateRoleException
     */
    public static function create(string $role): self
    {
        return new self($role);
    }

    public function __construct(string $role)
    {
        $message = implode(' ', [
            'The hierarchy cannot contain duplicate roles.',
            'The role "%s" has already been defined and cannot be overwritten or merged.',
        ]);

        $message = sprintf($message, $role);

        parent::__construct($message);
    }
}
