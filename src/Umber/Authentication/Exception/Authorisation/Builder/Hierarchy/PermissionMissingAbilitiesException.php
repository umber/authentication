<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionMissingAbilitiesException extends AbstractMessageRuntimeException
{
    /**
     * @return PermissionMissingAbilitiesException
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
            'The hierarchy expects that all permissions come with at least one ability.',
            'The permission scope "{{scope}}" has no abilities assigned to it so is considered useless.',
        ];
    }
}
