<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\Credential;

use Umber\Authentication\Authorisation\AuthorisationAwareInterface;

/**
 * {@inheritdoc}
 */
final class Credential implements CredentialInterface
{
    /** @var AuthorisationAwareInterface */
    private $authorisation;

    public function __construct(AuthorisationAwareInterface $authorisation)
    {
        $this->authorisation = $authorisation;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationRoles(): array
    {
        return $this->authorisation->getAuthorisationRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorisationPermissions(): array
    {
        return $this->authorisation->getAuthorisationPermissions();
    }
}
