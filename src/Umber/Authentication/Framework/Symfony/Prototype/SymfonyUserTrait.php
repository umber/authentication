<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony\Prototype;

use Umber\Authentication\Prototype\UserInterface as CommonUserInterface;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * A trait that should modify the umber common user to become like Symfony expects.
 *
 * @mixin SymfonyUserInterface
 * @mixin CommonUserInterface
 */
trait SymfonyUserTrait
{
    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::getUsername()
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::getPassword()
     */
    public function getPassword(): string
    {
        return 'password';
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::getSalt()
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::eraseCredentials()
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see CommonUserInterface::getAuthorisationRoles()
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->getAuthorisationRoles();
    }
}
