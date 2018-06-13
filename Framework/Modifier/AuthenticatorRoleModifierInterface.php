<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Modifier;

/**
 * A modifier class that can intercept roles before given to the framework.
 */
interface AuthenticatorRoleModifierInterface
{
    /**
     * Modify the roles before they are provided to the framework.
     *
     * @param string[] $roles
     *
     * @return string[]
     */
    public function modify(array $roles): array;
}
