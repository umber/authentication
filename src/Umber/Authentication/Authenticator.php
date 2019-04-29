<?php

declare(strict_types=1);

namespace Umber\Authentication;

use Umber\Authentication\Authorisation\Builder\Resolver\AuthorisationHierarchyResolverInterface;
use Umber\Authentication\Authorisation\Credential\CredentialAwareAuthorisation;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticatedCredentialException;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticationMethodException;
use Umber\Authentication\Exception\Resolver\UnsupportedAuthenticationMethodException;
use Umber\Authentication\Exception\Token\TokenExpiredException;
use Umber\Authentication\Exception\Token\TokenNotVerifiedException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Resolver\CredentialResolverInterface;
use Umber\Authentication\Storage\CredentialStorageInterface;

/**
 * An authenticator for resolving and storing user credentials.
 */
final class Authenticator
{
    /** @var CredentialStorageInterface */
    private $credentialStorage;

    /** @var CredentialResolverInterface */
    private $credentialResolver;

    /** @var AuthorisationHierarchyResolverInterface */
    private $authorisationHierarchyResolver;

    public function __construct(
        CredentialStorageInterface $credentialStorage,
        CredentialResolverInterface $credentialResolver,
        AuthorisationHierarchyResolverInterface $authorisationHierarchyResolver
    ) {
        $this->credentialStorage = $credentialStorage;
        $this->credentialResolver = $credentialResolver;
        $this->authorisationHierarchyResolver = $authorisationHierarchyResolver;
    }

    /**
     * Attempt to authenticate using the given authentication method.
     *
     * @throws UnauthorisedException
     *
     * @throws DuplicateRoleException
     * @throws RoleNotFoundException
     * @throws RoleNameInvalidException
     *
     * @throws DuplicatePermissionScopeException
     * @throws PermissionScopeNotFoundException
     * @throws PermissionScopeNameInvalidException
     * @throws PermissionSerialisationNameInvalidException
     * @throws PermissionAbilityNotFoundException
     * @throws PermissionAbilityNameInvalidException
     * @throws PermissionMissingAbilitiesException
     */
    public function authenticate(AuthenticationMethodInterface $method): void
    {
        if ($this->credentialStorage->isAuthenticated()) {
            return;
        }

        try {
            $credentials = $this->credentialResolver->resolve($method);
        } catch (CannotResolveAuthenticationMethodException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (CannotResolveAuthenticatedCredentialException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (UnsupportedAuthenticationMethodException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (TokenExpiredException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (TokenNotVerifiedException $exception) {
            throw UnauthorisedException::create($exception);
        }

        $hierarchy = $this->authorisationHierarchyResolver->resolve();
        $authorisation = new CredentialAwareAuthorisation($credentials, $hierarchy);

        $this->credentialStorage->authorise($authorisation);
    }

    /**
     * Return the authenticator's credential storage.
     */
    public function getCredentialStorage(): CredentialStorageInterface
    {
        return $this->credentialStorage;
    }
}
