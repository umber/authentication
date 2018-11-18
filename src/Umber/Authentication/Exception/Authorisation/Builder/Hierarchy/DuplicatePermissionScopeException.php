<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class DuplicatePermissionScopeException extends AbstractMessageRuntimeException
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
            'The hierarchy cannot contain duplicate permission scopes.',
            'The permission scope "{{scope}}" has already been defined and cannot be overwritten or merged.',
        ];
    }
}
