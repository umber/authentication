<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\Credential\User;

use Umber\Authentication\Prototype\UserInterface;

/**
 * A credential implementation that is a pass-through for user instances.
 */
final class UserCredential implements UserCredentialInterface
{
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationRoles(): array
    {
        return $this->user->getAuthorisationRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationPermissions(): array
    {
        return $this->user->getAuthorisationPermissions();
    }
}
