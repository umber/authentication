<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Credential;

use Umber\Authentication\Authorisation\AuthorisationInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * An implementation of authorisation interface aware of credentials.
 */
interface CredentialAwareAuthorisationInterface extends AuthorisationInterface
{
    /**
     * Return the credentials.
     */
    public function getCredentials(): CredentialInterface;
}
