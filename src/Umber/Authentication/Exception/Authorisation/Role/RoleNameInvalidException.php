<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Role;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class RoleNameInvalidException extends AbstractMessageRuntimeException
{
    /**
     * @return RoleNameInvalidException
     */
    public static function create(string $role): self
    {
        return new self([
            'name' => $role,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'A role name should only contain alphabetic characters and hyphens or underscores.',
            'The role name provided ("{{name}}") is invalid.',
        ];
    }
}
