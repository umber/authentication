<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionAbilityNotFoundException extends AbstractRuntimeException
{
    /**
     * @return DuplicateRoleException
     */
    public static function create(string $scope, string $ability): self
    {
        return new self([
            'scope' => $scope,
            'ability' => $ability,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getMessageTemplate(): array
    {
        return [
            'The permission ability "{{ability}}" was not found against the scope "{{scope}}".',
        ];
    }
}
