<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionScopeNotFoundException extends AbstractMessageRuntimeException
{
    /**
     * @return PermissionScopeNotFoundException
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
