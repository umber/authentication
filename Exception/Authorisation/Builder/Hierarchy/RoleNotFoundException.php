<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * {@inheritdoc}
 */
final class RoleNotFoundException extends AbstractRuntimeException
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
    public static function getMessageTemplate(): array
    {
        return [
            'The role "{{name}}" was not found.',
        ];
    }
}
