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
        return new self($permission);
    }

    public function __construct(string $permission)
    {
        $message = implode(' ', [
            'A transportable permission name should contain its ability.',
            'This is done using the format "permission:ability".',
            'Multiple abilities are possible through multiple entries of the same name.',
            'The permission "%s" is invalid.',
        ]);

        $message = sprintf($message, $permission);

        parent::__construct($message);
    }
}
