<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Permission;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * {@inheritdoc}
 */
final class PermissionScopeNameInvalidException extends AbstractRuntimeException
{
    /**
     * @return PermissionScopeNameInvalidException
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
    public static function getMessageTemplate(): array
    {
        return [
            'A permission scope name should only contain alphabetic characters and hyphens or underscores.',
            'The permission scope name provided ("{{scope}}") is invalid.',
        ];
    }
}
