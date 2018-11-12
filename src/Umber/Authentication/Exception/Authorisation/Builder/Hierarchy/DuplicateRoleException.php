<?php

declare(strict_types=1);

namespace Umber\Authentication\Exception\Authorisation\Builder\Hierarchy;

use Umber\Exception\Message\AbstractMessageRuntimeException;

/**
 * {@inheritdoc}
 */
final class DuplicateRoleException extends AbstractMessageRuntimeException
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
    public static function message(): array
    {
        return [
            'The hierarchy cannot contain duplicate roles.',
            'The role "{{name}}" has already been defined and cannot be overwritten or merged.',
        ];
    }
}