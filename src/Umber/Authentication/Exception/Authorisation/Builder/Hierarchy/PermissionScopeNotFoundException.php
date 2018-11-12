<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionScopeNotFoundException extends AbstractMessageRuntimeException
{
    /**
     * @return DuplicateRoleException
     */
    public static function create(string $scope): self
    {
        return new self([
            'scope' => $scope,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'The permission scope "{{scope}}" was not found.',
        ];
    }
}
