<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony\Bundle;

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
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::getPassword()
     */
    public function getPassword()
    {
        return 'password';
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::getSalt()
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @see SymfonyUserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see CommonUserInterface::getAuthorisationRoles()
     */
    public function getRoles()
    {
        return $this->getAuthorisationRoles();
    }
}
