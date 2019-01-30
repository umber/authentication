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
    public static function create(string $name): self
    {
        $message = implode(' ', [
            'The hierarchy cannot contain duplicate roles.',
            sprintf('The role "%s" has already been defined and cannot be overwritten or merged.', $name),
        ]);

        return new self($message);
    }
}
