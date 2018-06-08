<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionAbilityNameInvalidException extends AbstractRuntimeException
{
    /**
     * @return PermissionAbilityNameInvalidException
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
            'A permission ability should only contain alphabetic characters and hyphens or underscores.',
            'The permission ability provided ("{{ability}}") is invalid for scope "{{scope}}".',
        ];
    }
}
