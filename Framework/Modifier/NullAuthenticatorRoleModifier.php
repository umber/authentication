<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Modifier;

/**
 * {@inheritdoc}
 */
final class NullAuthenticatorRoleModifier implements AuthenticatorRoleModifierInterface
{
    /**
     * {@inheritdoc}
     */
    public function modify(array $roles): array
    {
        return $roles;
    }
}
