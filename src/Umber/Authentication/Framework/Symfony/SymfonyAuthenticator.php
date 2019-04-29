<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony;

use Umber\Authentication\Authenticator;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicatePermissionScopeException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\DuplicateRoleException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionAbilityNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionMissingAbilitiesException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\PermissionScopeNotFoundException;
use Umber\Authentication\Exception\Authorisation\Builder\Hierarchy\RoleNotFoundException;
use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionSerialisationNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Role\RoleNameInvalidException;
use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * An authenticator implementation for Symfony.
 */
final class SymfonyAuthenticator implements SimplePreAuthenticatorInterface
{
    /** @var Authenticator */
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * Check the supported providers.
     *
     * @param string $provider
     */
    public function supportsToken(TokenInterface $token, $provider): bool
    {
        return ($token instanceof PreAuthenticatedToken)
            && ($token->getProviderKey() === $provider);
    }

    /**
     * Create a token for the framework.
     *
     * @param string $provider
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
    public function createToken(Request $request, $provider): PreAuthenticatedToken
    {
        try {
            $header = new SymfonyRequestAuthorisationHeader($request);
        } catch (MalformedAuthorisationHeaderException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (MissingCredentialsException $exception) {
            throw UnauthorisedException::create($exception);
        }

        $this->authenticator->authenticate($header);

        return new PreAuthenticatedToken(
            $header->getType(),
            $header->getCredentials(),
            $provider
        );
    }

    /**
     * Authenticate the token with the framework.
     *
     * @param string $provider
     *
     * @throws UnauthorisedException
     */
    public function authenticateToken(
        TokenInterface $token,
        UserProviderInterface $userProvider,
        $provider
    ): PreAuthenticatedToken {
        $credentials = $this->authenticator->getCredentialStorage()->getCredentials();

        return new PreAuthenticatedToken(
            $token->getUser(),
            $token->getCredentials(),
            $provider,
            $credentials->getAuthorisationRoles()
        );
    }
}
