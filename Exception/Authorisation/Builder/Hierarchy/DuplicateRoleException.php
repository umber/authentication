<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Common\Exception\AbstractRuntimeException;

/**
 * {@inheritdoc}
 */
final class DuplicateRoleException extends AbstractRuntimeException
{
    /**
     * @return DuplicateRoleException
     */
    public static function create(string $name): self
    {
        return new self([
            'name' => $name,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getMessageTemplate(): array
    {
        return [
            'The hierarchy cannot contain duplicate roles.',
            'The role "{{name}}" has already been defined and cannot be overwritten or merged.',
        ];
    }
}
