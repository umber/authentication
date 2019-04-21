<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Credential;

use Umber\Authentication\Authorisation\Authorisation;
use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Resolver\Credential\CredentialInterface;

/**
 * {@inheritdoc}
 */
final class CredentialAwareAuthorisation implements CredentialAwareAuthorisationInterface
{
    /** @var CredentialInterface */
    private $credentials;

    /** @var Authorisation */
    private $authorisation;

    /**
     * @throws RoleNotFoundException
     * @throws PermissionAbilityNotFoundException
     * @throws PermissionScopeNotFoundException
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionScopeNameInvalidException
     * @throws PermissionSerialisationNameInvalidException
     */
    public function __construct(CredentialInterface $credentials, AuthorisationHierarchy $hierarchy)
    {
        $this->credentials = $credentials;

        $this->authorisation = new Authorisation(
            $credentials->getAuthorisationRoles(),
            $credentials->getAuthorisationPermissions(),
            $hierarchy
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): CredentialInterface
    {
        return $this->credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->authorisation->getRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole(string $role): bool
    {
        return $this->authorisation->hasRole($role);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions(): array
    {
        return $this->authorisation->getPermissions();
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission(string $scope, string $attribute): bool
    {
        return $this->authorisation->hasPermission($scope, $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassivePermissions(): array
    {
        return $this->authorisation->getPassivePermissions();
    }
}
