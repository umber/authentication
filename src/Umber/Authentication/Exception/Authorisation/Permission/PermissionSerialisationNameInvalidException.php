<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Exception;

/**
 * An exception thrown when a permission cannot be un-serialised.
 */
final class PermissionSerialisationNameInvalidException extends Exception
{
    /**
     * @return PermissionSerialisationNameInvalidException
     */
    public static function create(string $permission): self
    {
        $message = implode(' ', [
            'A transportable permission name should contain its ability.',
            'This is done using the format "permission:ability".',
            'Multiple abilities are possible through multiple entries of the same name.',
            sprintf('The permission "%s" is invalid.', $permission),
        ]);

        return new self($message);
    }
}
