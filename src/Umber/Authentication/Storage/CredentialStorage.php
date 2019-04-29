<?php

declare(strict_types=1);

namespace Umber\Authentication\Storage;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisationInterface;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

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
    public function isAuthenticated(): bool
    {
        return $this->authorisation !== null;
    }
}
