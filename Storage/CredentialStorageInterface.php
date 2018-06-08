<?php

declare(strict_types=1);

namespace Umber\Authentication\Storage;

use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisationInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedUserException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Prototype\UserInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * A credentials storage.
 */
interface CredentialStorageInterface
{
    /**
     * Authorise credentials.
     */
    public function authorise(CredentialAwareAuthorisationInterface $authorisation): void;

    /**
     * Return the authorised credentials.
     *
     * @throws UnauthorisedException When the credentials are not authenticated.
     */
    public function getCredentials(): CredentialInterface;

    /**
     * Return the user authorisation.
     *
     * @throws UnauthorisedException When the credentials are not authenticated.
     */
    public function getAuthorisation(): CredentialAwareAuthorisationInterface;

    /**
     * Return the authenticated user.
     *
     * @throws UnauthorisedException When the credentials are not authenticated.
     * @throws CannotResolveAuthenticatedUserException When the user is not resolved with the credentials.
     */
    public function getUser(): UserInterface;

    /**
     * Check if a user has been authenticated.
     */
    public function isAuthenticated(): bool;
}
