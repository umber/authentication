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
        return $this->getAuthorisation()->getCredentials();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisation(): CredentialAwareAuthorisationInterface
    {
        $authorisation = $this->authorisation;

        if ($authorisation === null) {
            throw UnauthorisedException::create();
        }

        return $authorisation;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        $credentials = $this->getAuthorisation()->getCredentials();

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
