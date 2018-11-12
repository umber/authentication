<?php

declare(strict_types=1);

namespace Umber\Authentication\Storage;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisationInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedUserException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Resolver\Credential\User\UserCredentialInterface;

/**
 * {@inheritdoc}
 */
final class CredentialStorage implements CredentialStorageInterface
{
    /** @var CredentialAwareAuthorisationInterface|null */
    private $authorisation;

    /**
     * {@inheritdoc}
     */
    public function authorise(CredentialAwareAuthorisationInterface $authorisation): void
    {
        $this->authorisation = $authorisation;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): CredentialInterface
    {
        if ($this->isAuthenticated() === false) {
            throw UnauthorisedException::create();
        }

        return $this->authorisation->getCredentials();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisation(): CredentialAwareAuthorisationInterface
    {
        if ($this->isAuthenticated() === false) {
            throw UnauthorisedException::create();
        }

        return $this->authorisation;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        if ($this->isAuthenticated() === false) {
            throw UnauthorisedException::create();
        }

        $credentials = $this->authorisation->getCredentials();

        if ($credentials instanceof UserCredentialInterface) {
            return $credentials->getUser();
        }

        throw CannotResolveAuthenticatedUserException::create();
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthenticated(): bool
    {
        return $this->authorisation !== null;
    }
}
