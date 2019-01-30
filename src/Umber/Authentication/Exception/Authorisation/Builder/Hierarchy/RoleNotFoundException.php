<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Exception;

/**
 * An exception thrown when a role cannot found.
 */
final class RoleNotFoundException extends Exception
{
    /**
     * @return RoleNotFoundException
     */
    public static function create(string $role): self
    {
        $message = 'The role "%s" was not found.';
        $message = sprintf($message, $role);

        return new self($message);
    }
}
