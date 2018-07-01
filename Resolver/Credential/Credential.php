<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\Credential;

use Umber\Authentication\Prototype\Authorisation\AuthorisationAwareInterface;

/**
 * {@inheritdoc}
 */
final class Credential implements CredentialInterface
{
    private $aware;

    public function __construct(AuthorisationAwareInterface $aware)
    {
        $this->aware = $aware;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationRoles(): array
    {
        return $this->aware->getAuthorisationRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationPermissions(): array
    {
        return $this->aware->getAuthorisationPermissions();
    }
}
