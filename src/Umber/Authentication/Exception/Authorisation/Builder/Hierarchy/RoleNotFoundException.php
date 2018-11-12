<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class RoleNotFoundException extends AbstractMessageRuntimeException
{
    /**
     * @return DuplicateRoleException
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
            'The role "{{name}}" was not found.',
        ];
    }
}
