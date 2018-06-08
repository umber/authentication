<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation;

use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;

/**
 * {@inheritdoc}
 */
final class Role implements RoleInterface
{
    private const NAME_REGEX = '/^([a-z]+[a-z\-\_]?[a-z]+)+$/';

    private $name;
    private $permissions;

    /**
     * @param PermissionInterface[] $permissions
     */
    public function __construct(string $name, array $permissions)
    {
        if (preg_match(self::NAME_REGEX, strtolower($name)) !== 1) {
            throw RoleNameInvalidException::create($name);
        }

        $this->name = strtolower($name);
        $this->permissions = $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassivePermissions(): array
    {
        return $this->permissions;
    }
}
