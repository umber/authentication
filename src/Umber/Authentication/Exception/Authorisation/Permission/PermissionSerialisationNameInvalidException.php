<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionSerialisationNameInvalidException extends AbstractMessageRuntimeException
{
    /**
     * @return PermissionSerialisationNameInvalidException
     */
    public static function create(string $permission): self
    {
        return new self([
            'permission' => $permission,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'A transportable permission name should contain its ability.',
            'This is done using the format "permission:ability".',
            'Multiple abilities are possible through multiple entries of the same name.',
            'The permission given ("{{permission}}") is invalid.',
        ];
    }
}
